<template>
  <div class="table__menubar" v-click-away="() => (openDropdownMenu = false)">
    <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
      <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363" />
    </svg>
    <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
      <li v-if="hasPermission('organization-element:view')">
        <router-link :to="{ name: 'organization-element-show', params: { id: organization_element.id } }">{{ $t('View Details') }}</router-link>
      </li>
      <li v-if="hasPermission('organization-element:edit')">
        <router-link :to="{ name: 'organization-element-edit', params: { id: organization_element.id } }">{{ $t('Edit') }}</router-link>
      </li>
      <li v-if="hasPermission('organization-element:edit') && status == 'Inactive'">
        <a @click.prevent="changeStatus('Active')"> {{ $t('Activate') }}</a>
      </li>
      <li v-if="hasPermission('organization-element:edit') && status == 'Active'">
        <a @click.prevent="changeStatus('Inactive')">{{ $t('Deactivate') }}</a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
import { ref } from '@vue/reactivity'
import { inject } from '@vue/runtime-core'

const props = defineProps({
  organization_element: {
    type: Object,
    required: true,
  },
})

const emitter = inject('emitter')
const $vfm = inject('$vfm')
const emit = defineEmits(['onDeactivate'])

const openDropdownMenu = ref(false)
const status = ref(props.organization_element.status)

const changeStatus = (status) => {
  const title = status == 'Active' ? 'Activate organizational element?' : 'Deactivate organizational element?'
  const description =
    status == 'Active'
      ? trans('Are you sure you really want to activate the organizational element “:name”?', {
          name: props.organization_element.name,
        })
      : trans('Are you sure that you really want to deactivate the organizational element “:name” and thus also remove all internal user assignments?', { name: props.organization_element.name })

  $vfm.show('confirmation', {
    title,
    description,
    yesClick: () => updateStatus(status),
  })
}

const updateStatus = async (value) => {
  const res = await axios.put(
    route('organization-elements.update-status', {
      organization_element: props.organization_element.id,
    }),
    { status: value }
  )

  const description =
    value == 'Active' ? 'The organizational element “:name” has been successfully activated.' : 'The “:name” organizational element has been successfully deactivated and all internal user assignments removed.'

  $vfm.show('success-notification', {
    description: trans(description, { name: props.organization_element.name }),
  })
  status.value = value
  emitter.emit(`update-status-${props.organization_element.id}`, value)
  if (value != 'Active') {
    emit(`onDeactivate`)
  }
}
</script>

<style lang="scss" scoped></style>

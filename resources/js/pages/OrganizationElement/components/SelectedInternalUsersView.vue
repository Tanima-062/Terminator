<template>
  <div class="flex w-[498px] h-max flex-col">
    <div class="head rounded-t-[4px] bg-heading h-[31px] flex pr-[22px] items-center px-[22px]">
      <h3 class="w-1/2 text-white text-16 font-semibold">{{ $t('Name') }}</h3>
      <h3 class="w-1/2 text-white text-16 font-semibold">{{ $t('Role') }}</h3>
    </div>
    <div class="body rounded-b-[4px] flex flex-col divide-y divide-input border-b border-x border-input w-full" :class="{ 'py-[11px]': !selected_users.length }">
      <div class="row px-[22px] py-[11px] flex" v-for="(user, index) in $attrs.show_all ? selected_users : selected_users.slice(0, 5)" :key="index">
        <a :href="$router.resolve({ name: 'internal-user-show', params: { id: user.internal_user_id } }).href" class="text-[#13A3E5] text-16 w-1/2" target="_blank">{{ user.label }}</a>
        <p class="text-[#636363] text-16 w-1/2">{{ company_roles.roles.value.find(({ id }) => id == user.role).name }}</p>
      </div>
    </div>
    <div class="footer flex mt-[6px]">
      <div class="show-more flex flex-col w-full">
        <div class="wrapper flex gap-2 items-center justify-end" v-if="!$attrs.show_all && selected_users.length > 5">
          <PillTooltip :items="selected_users.map(({ label }) => label).slice(5)" />
          <p class="text-[#13A3E5] cursor-pointer text-[13px] leading-5 font-poppins" @click="showViewModal">
            {{ $t('View All') }}
          </p>
        </div>
        <div class="wrapper" :class="{ 'mt-[25px]': !$attrs.show_all && selected_users.length < 5 }" v-if="!$attrs.show_all && !$attrs.hide_edit_btn">
          <ButtonGradient class="w-max flex gap-[14px] items-center justify-center p-0" type="button" @click="editInternalUser">
            <PencilIcon />
            <span>{{ $t('Edit') }}</span>
          </ButtonGradient>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { inject } from '@vue/runtime-core'
import { computed, ref } from '@vue/reactivity'
import PillTooltip from '@/components/utils/PillTooltip.vue'
import PencilIcon from '@/components/icons/PencilIcon.vue'
import ButtonGradient from '@/components/button/Gradient.vue'
import ViewAllInternalUsersModal from './ViewAllInternalUsersModal.vue'
import InternalUserModificationModal from './InternalUserModificationModal.vue'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import { useAttrs } from 'vue'

const emit = defineEmits(['updateSubordinateUsers'])
const $vfm = inject('$vfm')

const company_roles = storeToRefs(companyRolesStore())

const attrs = useAttrs()
const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
  users: {
    type: Array,
    default: () => [],
  },
  value: {
    type: Array,
    required: false,
    default: () => [],
  },
})

const selected_users = computed(() => props.users.filter(({ value }) => props.value.includes(value)))

const showViewModal = () => {
  $vfm.show({
    component: ViewAllInternalUsersModal,
    bind: {
      title: 'Internal user assignment',
      value: props.value,
      users: props.users,
      roles: props.roles,
      hide_edit_btn: attrs.hide_edit_btn,
    },
    on: {
      updateSubordinateUsers: (users) => {
        emit('updateSubordinateUsers', users)
      },
    },
  })
}

const editInternalUser = () => {
  const options = {
    component: InternalUserModificationModal,
    bind: {
      title: 'Edit internal user assignment',
      value: props.value,
      roles: props.roles,
      users: props.users,
      edit: true,
    },
    on: {
      save: (users) => {
        emit('updateSubordinateUsers', users)
      },
    },
  }
  $vfm.show(options)
}
</script>

<template>
  <div class="organization-elements w-[785px] flex flex-col col-span-3 mt-5">
    <div class="flex w-full min-h-[31px] items-center pr-[22px] bg-[#AB326F] rounded-t-[4px] pl-[25px] plr-[10px]">
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[9%]">
        {{ $t('Status') }}
      </div>
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[15%]">
        {{ $t('Created on') }}
      </div>
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[19%]">
        {{ $t('Last updated on') }}
      </div>
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[19%]">
        {{ $t('Hierarchy Level') }}
      </div>
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[20%]" :class="{'w-[38%]': !$attrs.not_editable}">
        {{ $t('Name') }}
      </div>
      <div class="text-[16px] leading-[19px] text-[#ffffff] w-[18%]"  v-if="$attrs.not_editable"></div>
    </div>

    <div class="elements divide-y divide-input border-b-[1px] border-x-[1px] border-[#E6DEE5]">
      <div class="flex justify-center pl-[25px] pr-[10px] min-h-[45px] items-center" v-for="(element, index) in attrs.modelValue" :key="index">
        <div class="td w-[9%]">
          <ActiveIcon v-if="element.status == 'Active'" />
          <InactiveIcon v-else />
        </div>
        <div class="td w-[15%]" v-date-format="element.created_at"></div>
        <div class="td w-[19%]" v-date-format="element.updated_at ?? '-'"></div>
        <div class="td w-[19%]">
          {{ element.hierarchy_level === null ? $t('None') : element.hierarchy_level }}
        </div>
        <div class="td w-[20%]" :class="{'w-[38%]': $attrs.not_editable}">{{ element.name }}</div>
        <div class="td w-[18%] flex justify-end"  v-if="!$attrs.not_editable">
          <PencilBoxIcon class="cursor-pointer" @click="editHierarchyElementModalHandle(index)" /><EyeBoxIcon class="ml-3 cursor-pointer" @click="openDetailsModal(index)" />
          <DisableBoxIcon v-if="element.status == 'Active'" class="ml-3 cursor-pointer" @click="updateHierarchyElementStatusModal(index)" />
          <!-- <ActivateBoxIcon v-else class="ml-3 cursor-pointer" @click="updateHierarchyElementStatusModal(index)" /> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import PencilBoxIcon from '@/components/icons/PencilBoxIcon.vue'
import EyeBoxIcon from '@/components/icons/EyeBoxIcon.vue'
import DisableBoxIcon from '@/components/icons/DisableBoxIcon.vue'
import ActivateBoxIcon from '@/components/icons/ActivateBoxIcon.vue'
import ActiveIcon from '@/components/icons/ActiveIcon.vue'
import InactiveIcon from '@/components/icons/InactiveIcon.vue'
import HierarchyElementModificationModal from './HierarchyElementModificationModal.vue'
import { trans } from 'laravel-vue-i18n'
import { inject } from '@vue/runtime-core'
import { computed, reactive } from '@vue/reactivity'
import HierarchyElementDetailsModal from './HierarchyElementDetailsModal.vue'
import { useAttrs } from 'vue'

const attrs = useAttrs()
const props = defineProps({
  company_name: {
    type: String,
    required: true,
  },
})

const used_subordinate_roles = computed(() => {
  return attrs.modelValue.map((el) => (el.hierarchy_level === null ? [] : el.direct_subordinated_role)).flat()
})

const hierarchy_levels = computed(() => {
  let levels = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10].filter((n) => !attrs.modelValue.some((el) => el.hierarchy_level == n))
  levels = levels.map((i) => ({ value: i, label: i }))
  levels.unshift({ label: trans('None'), value: null })
  return levels
})

const $vfm = inject('$vfm')
const emit = defineEmits(['onUpdate', 'update:modelValue'])

const editHierarchyElementModalHandle = (index) => {
  const levels = [...hierarchy_levels.value]
  const element = attrs.modelValue[index]
  if (element.hierarchy_level !== null) levels.push({ label: element.hierarchy_level, value: element.hierarchy_level })

  levels.sort((a, b) => (a.value === null ? -1 : a.label < b.label ? -1 : 1))

  const options = {
    component: HierarchyElementModificationModal,
    bind: {
      title: trans('Edit Organizational Element'),
      hierarchy_levels: levels,
      value: element,
      used_subordinate_roles: used_subordinate_roles.value,
    },
    on: {
      saveElement: (element) => {
        const updated_data = [...attrs.modelValue]
        updated_data[index] = element
        emit('update:modelValue', updated_data)
      },
    },
  }
  $vfm.show(options)
}

const openDetailsModal = (index) => {
  const options = {
    component: HierarchyElementDetailsModal,
    bind: {
      value: attrs.modelValue[index],
    },
    on: {
      openEditModal: () => editHierarchyElementModalHandle(index),
    },
  }
  $vfm.show(options)
}

const updateHierarchyElementStatusModal = (index) => {
  const element = attrs.modelValue[index]
  const status = element.status == 'Inactive' ? 'Active' : 'Inactive'

  const title = status == 'Active' ? trans('Activate Organizational Element?') : trans('Deactivate Organizational Element?')

  const description =
    status == 'Active'
      ? trans('Are you sure you really want to activate the Element “:name”?', {
          name: element.name,
        })
      : trans('A deactivation cannot be reversed. Are you sure you really want to deactivate the Element “:name”?', { name: element.name })

  $vfm.show('confirmation', {
    title,
    description,
    yesText: 'Yes',
    yesClick: () => {
      const updated_data = [...attrs.modelValue]
      updated_data[index] = { ...updated_data[index], status }
      emit('update:modelValue', updated_data)
    },
  })
}
</script>

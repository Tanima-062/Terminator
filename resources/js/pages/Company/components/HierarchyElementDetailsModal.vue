<template>
  <vue-final-modal
    v-slot="{ close }"
    :click-to-close="true"
    :esc-to-close="true"
    classes="flex justify-center items-center"
    content-class="content bg-white px-[54px] pt-12 pb-[50px] rounded-sm w-[572px] grid grid-cols-2"
  >
    <h1 class="text-[20px] mb-12 text-heading leading-6 font-semibold col-span-2">
      {{ $t('Organizational Element Details') }}
    </h1>

    <div class="field">
      <h3 class="label text-[16px] font-semibold text-input mb-[10px]">
        {{ $t('Hierarchy Level') }}
      </h3>
      <p class="value text-[16px] leading-8 text-bodyText">
        {{ value.hierarchy_level === null ? $t('None') : value.hierarchy_level }}
      </p>
    </div>

    <div class="field">
      <h3 class="label text-[16px] font-semibold text-input mb-[10px]">
        {{ $t('Name') }}
      </h3>
      <p class="value text-[16px] leading-8 text-bodyText">{{ value.name }}</p>
    </div>

    <div class="spacer mb-[43px] col-span-2"></div>
    <div class="field">
      <h3 class="label label text-[16px] font-semibold text-input mb-[10px]">
        {{ $t('Responsible Role:slashs', { slash: '/' }) }}
      </h3>
      <div class="value flex items-center">
        <span class="w-[140px] whitespace-nowrap text-[16px] text-bodyText" :class="{ 'text-ellipsis overflow-hidden': roles_label.length > 2 }">
          {{ roles_label.slice(0, 2).join(', ') }}
        </span>
        <div class="wrapper group relative" v-if="roles_label.length > 2">
          <Pill :length="roles_label.length - 2" :radius="10" />
          <div
            class="tooltip group-hover:visible invisible absolute top-[120%] z-20 w-max bg-white flex flex-col border border-input px-[10px] py-[9px] gap-[10px] overflow-y-auto max-h-[100px] rounded-[5px] shadow-[1px_1px_7px_rgba(114,_114,_114,_0.25)] transition-all duration-100 ease-linear"
          >
            <div class="flex items-center text-[#636363] text-[16px] leading-4" v-for="label in roles_label.slice(2, roles_label.length)">
              {{ label }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="field">
      <h3 class="label label text-[16px] font-semibold text-input mb-[10px]">
        {{ $t('Directly subordinated Role:slashs', { slash: '/' }) }}
      </h3>
      <div class="value flex items-center">
        <span class="w-[140px] whitespace-nowrap text-[16px] text-bodyText" :class="{ 'text-ellipsis overflow-hidden': subodinate_label.length > 2 }">
          {{ subodinate_label.slice(0, 2).join(', ') }}
        </span>
        <div class="wrapper group relative" v-if="subodinate_label.length > 2">
          <Pill :length="subodinate_label.length - 2" :radius="10" />
          <div
            class="tooltip group-hover:visible invisible absolute top-[120%] z-20 w-max bg-white flex flex-col border border-input px-[10px] py-[9px] gap-[10px] overflow-y-auto max-h-[100px] rounded-[5px] shadow-[1px_1px_7px_rgba(114,_114,_114,_0.25)] transition-all duration-100 ease-linear"
          >
            <div class="flex items-center text-[#636363] text-[16px] leading-4" v-for="label in subodinate_label.slice(2, subodinate_label.length)">
              {{ label }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="spacer mb-[80px] col-span-2"></div>

    <ButtonGradient
      class="w-full h-[48px] flex items-center justify-center gap-[14px]"
      type="button"
      @click="
        () => {
          close()
          $emit('openEditModal')
        }
      "
    >
      <EditIcon />
      <span>
        {{ $t('Edit') }}
      </span>
    </ButtonGradient>
  </vue-final-modal>
</template>

<script setup>
import ButtonGradient from '@/components/button/Gradient.vue'
import { computed } from '@vue/reactivity'
import EditIcon from '@/components/icons/EditIcon.vue'
import Pill from '@/components/utils/Pill.vue'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'

const company_roles = storeToRefs(companyRolesStore())
const roles = computed(() => {
  if (company_roles.roles.value.length) {
    return company_roles.roles.value.map(({ id, name }) => ({ value: id, label: name }))
  }
  return []
})

const props = defineProps({
  value: {
    type: Object,
    required: false,
    default: () => ({
      hierarchy_level: '',
      name: '',
      responsible_role: [],
      direct_subordinated_role: [],
    }),
  },
})
const roles_label = computed(() => roles.value.filter(({ value }) => props.value.responsible_role.includes(value)).map(({ label }) => label))
const subodinate_label = computed(() => roles.value.filter(({ value }) => props.value.direct_subordinated_role.includes(value)).map(({ label }) => label))
</script>

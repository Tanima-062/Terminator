<template>
  <div class="appointments">
    <div class="grid grid-cols-4 h-[43px] content-center px-[35px] bg-[#AB326F] rounded-t-[4px]">
      <p class="text-[16px] leading-[19px] text-white font-roboto font-bold">
        {{ $t('Appointment ID') }}
      </p>
      <p class="text-[16px] leading-[19px] text-white font-roboto font-bold">
        {{ $t('Appointment Date') }}
      </p>
      <p class="text-[16px] leading-[19px] text-white font-roboto font-bold">
        {{ $t('Appointment Time') }}
      </p>
      <p class="text-[16px] leading-[19px] text-white font-roboto font-bold">
        {{ $t('Control status (Appointment)') }}
      </p>
      <p class="text-[16px] leading-[19px] text-white font-roboto font-bold"></p>
    </div>
    <div class="elements divide-y divide-[#C5C5C5] border-b-[1px] border-x-[1px] border-[#C5C5C5] rounded-b" :class="{ 'h-4': !appointments.length }">
      <div class="grid grid-cols-4 content-center px-[35px] h-[61px]" v-for="appoinment in appointments">
        <p class="text-[#292929] text-16">{{ appoinment.prefix_id }}</p>
        <p class="text-[#292929] text-16" v-date-format="appoinment.appointment_date"></p>
        <p class="text-[#292929] text-16">{{ [appoinment.appointment_time.split(':')[0], appoinment.appointment_time.split(':')[1]].join(':') }}</p>
        <p class="text-[#292929] text-16">{{ appoinment.control_status_appointment ? $t(appoinment.control_status_appointment) : 'None/empty' }}</p>
      </div>
    </div>
    <GradientButton v-if="!$attrs.hide_create_btn && contact_data_record.contact_record_status == 'Confirmed'" class="mt-6 w-[229px] whitespace-nowrap" @click="assignAppointment">{{
      $t('Enter new appointment')
    }}</GradientButton>
  </div>
</template>

<script setup>
import GradientButton from '@/components/button/Gradient.vue'
import AssignAppointmentModal from './AssignAppointmentModal.vue'
import { inject, computed, useAttrs } from 'vue'
import { trans } from 'laravel-vue-i18n'

const attrs =useAttrs()
const props = defineProps({
  contact_data_record: {
    type: Object,
    required: true,
  },
})

const appointments = computed(() => {
  return attrs.modelValue
})

const emit = defineEmits('update:modelValue')
const $vfm = inject('$vfm')

const assignAppointment = () => {
  $vfm.show({
    component: AssignAppointmentModal,

    bind: {
      contact_data_record_id: props.contact_data_record.id,
    },

    on: {
      created: (data) => {
        emit('update:modelValue', [...appointments.value, data])
        $vfm.show('success-notification', {
          description: trans('The appointment was created successfully.'),
        })
      },
    },
  })
}
</script>

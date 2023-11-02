<template>
  <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]">
    <h1 class="text-formHeading text-title font-poppins mb-[60px]">
      {{ $t('Workflow Settings') }}
    </h1>

    <form class="grid grid-cols-3" @submit.prevent="save">
      <h2 class="form-sub-heading text-heading text-formSubHeading col-span-3 mb-[38px]">{{ $t('Limits') }}</h2>

      <TextInput class="w-[290px] text-[#585858]" v-model="form.call_attempt_limit" label="Call attempt limit" :error="v$.call_attempt_limit.$errors.length > 0" @input="v$.call_attempt_limit.$touch()"></TextInput>
      <TextInput class="w-[290px] text-[#585858]" v-model="form.contact_limit" label="Contact limit (rounds) in case of no interest" :error="v$.contact_limit.$errors.length > 0" @input="v$.contact_limit.$touch()"></TextInput>

      <div class="spacer mb-[60px] col-span-3"></div>

      <h2 class="form-sub-heading text-heading text-formSubHeading col-span-3 mb-[38px]">{{ $t('Workflow steps: costs and revenues') }}</h2>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Contact record creation') }}</h3>
      <ChfInput v-model="form.contact_record_creation_cost" label="Cost" :error="v$.contact_record_creation_cost.$errors.length > 0" @input="v$.contact_record_creation_cost.$touch()"></ChfInput>
      <ChfInput v-model="form.contact_record_creation_revenue" label="Revenue per contract" :error="v$.contact_record_creation_revenue.$errors.length > 0" @input="v$.contact_record_creation_revenue.$touch()"></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Data verification update') }}</h3>
      <ChfInput v-model="form.data_verification_cost" label="Cost" :error="v$.data_verification_cost.$errors.length > 0" @input="v$.data_verification_cost.$touch()"></ChfInput>
      <ChfInput v-model="form.data_verification_revenue" label="Revenue per contract" :error="v$.data_verification_revenue.$errors.length > 0" @input="v$.data_verification_revenue.$touch()"></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Lead quality check') }}</h3>
      <ChfInput v-model="form.lead_quality_check_cost" label="Cost" :error="v$.lead_quality_check_cost.$errors.length > 0" @input="v$.lead_quality_check_cost.$touch()"></ChfInput>
      <ChfInput v-model="form.lead_quality_check_revenue" label="Revenue per contract" :error="v$.lead_quality_check_revenue.$errors.length > 0" @input="v$.lead_quality_check_revenue.$touch()"></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Edit lead quality topics') }}</h3>
      <ChfInput v-model="form.edit_lead_quality_topics_cost" label="Cost" :error="v$.edit_lead_quality_topics_cost.$errors.length > 0" @input="v$.edit_lead_quality_topics_cost.$touch()"></ChfInput>
      <ChfInput
        v-model="form.edit_lead_quality_topics_revenue"
        label="Revenue per contract"
        :error="v$.edit_lead_quality_topics_revenue.$errors.length > 0"
        @input="v$.edit_lead_quality_topics_revenue.$touch()"
      ></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Appointment contact') }}</h3>
      <ChfInput v-model="form.appointment_contact_cost" label="Cost" :error="v$.appointment_contact_cost.$errors.length > 0" @input="v$.appointment_contact_cost.$touch()"></ChfInput>
      <ChfInput v-model="form.appointment_contact_revenue" label="Revenue per contract" :error="v$.appointment_contact_revenue.$errors.length > 0" @input="v$.appointment_contact_revenue.$touch()"></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Appointment quality check') }}</h3>
      <ChfInput v-model="form.appointment_quality_check_cost" label="Cost" :error="v$.appointment_quality_check_cost.$errors.length > 0" @input="v$.appointment_quality_check_cost.$touch()"></ChfInput>
      <ChfInput
        v-model="form.appointment_quality_check_revenue"
        label="Revenue per contract"
        :error="v$.appointment_quality_check_revenue.$errors.length > 0"
        @input="v$.appointment_quality_check_revenue.$touch()"
      ></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Edit appointment quality topics') }}</h3>
      <ChfInput v-model="form.edit_appointment_quality_topics_cost" label="Cost" :error="v$.edit_appointment_quality_topics_cost.$errors.length > 0" @input="v$.edit_appointment_quality_topics_cost.$touch()"></ChfInput>
      <ChfInput
        v-model="form.edit_appointment_quality_topics_revenue"
        label="Revenue per contract"
        :error="v$.edit_appointment_quality_topics_revenue.$errors.length > 0"
        @input="v$.edit_appointment_quality_topics_revenue.$touch()"
      ></ChfInput>
      <div class="spacer col-span-3 mb-11"></div>

      <h3 class="inputs-title text-input text-formLabel mb-6 col-span-3">{{ $t('Carry out appointment reminder') }}</h3>
      <ChfInput v-model="form.carry_out_appointment_reminder_cost" label="Cost" :error="v$.carry_out_appointment_reminder_cost.$errors.length > 0" @input="v$.carry_out_appointment_reminder_cost.$touch()"></ChfInput>
      <ChfInput
        v-model="form.carry_out_appointment_reminder_revenue"
        label="Revenue per contract"
        :error="v$.carry_out_appointment_reminder_revenue.$errors.length > 0"
        @input="v$.carry_out_appointment_reminder_revenue.$touch()"
      ></ChfInput>
      <div class="spacer col-span-3 mb-[100px]"></div>

      <ButtonGradient class="w-[187px] col-span-3" type="submit" :disabled="form.busy">
        {{ $t('Save') }}
      </ButtonGradient>
    </form>
  </div>
</template>

<script setup>
import ButtonGradient from '@/components/button/Gradient.vue'
import { reactive, onMounted } from 'vue'
import TextInput from '@/components/form/TextInput.vue'
import ChfInput from './components/ChfInput.vue'
import { Form as vForm } from 'vform'
import useVuelidate from '@vuelidate/core'
import { integer } from '@vuelidate/validators'
import { notificationShowStore as useNotification } from '@/store/notification'
import axios from 'axios'

const notification = useNotification()

const form = reactive(
  new vForm({
    call_attempt_limit: 10,
    contact_limit: 5,
    contact_record_creation_cost: '',
    contact_record_creation_revenue: '',
    data_verification_cost: '',
    data_verification_revenue: '',
    lead_quality_check_cost: '',
    lead_quality_check_revenue: '',
    edit_lead_quality_topics_cost: '',
    edit_lead_quality_topics_revenue: '',
    appointment_contact_cost: '',
    appointment_contact_revenue: '',
    appointment_quality_check_cost: '',
    appointment_quality_check_revenue: '',
    edit_appointment_quality_topics_cost: '',
    edit_appointment_quality_topics_revenue: '',
    carry_out_appointment_reminder_cost: '',
    carry_out_appointment_reminder_revenue: '',
  })
)

const valid_chf_format = (value) => {
  const decimal_places_length = value.split('.')[1]?.length
  return isFinite(value) && (value.includes('.') ? decimal_places_length <= 2 && decimal_places_length >= 1 : true)
}

const rules = {
  call_attempt_limit: {
    integer,
  },
  contact_limit: {
    integer,
  },
  contact_data_record_limit: {
    integer,
  },
  contact_record_creation_cost: { valid_chf_format },
  contact_record_creation_revenue: { valid_chf_format },
  data_verification_cost: { valid_chf_format },
  data_verification_revenue: { valid_chf_format },
  lead_quality_check_cost: { valid_chf_format },
  lead_quality_check_revenue: { valid_chf_format },
  edit_lead_quality_topics_cost: { valid_chf_format },
  edit_lead_quality_topics_revenue: { valid_chf_format },
  appointment_contact_cost: { valid_chf_format },
  appointment_contact_revenue: { valid_chf_format },
  appointment_quality_check_cost: { valid_chf_format },
  appointment_quality_check_revenue: { valid_chf_format },
  edit_appointment_quality_topics_cost: { valid_chf_format },
  edit_appointment_quality_topics_revenue: { valid_chf_format },
  carry_out_appointment_reminder_cost: { valid_chf_format },
  carry_out_appointment_reminder_revenue: { valid_chf_format },
}

const v$ = useVuelidate(rules, form)

const getWorkflowSettings = async () => {
  const { data } = await axios.get(route('workflow-settings'))
  Object.assign(form, data)
}

const save = async () => {
  try {
    form.post(route('workflow-settings.save'))
    // notification.success('Hello world!')
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  getWorkflowSettings()
})
</script>

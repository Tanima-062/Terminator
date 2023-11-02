<template>
  <vue-final-modal v-slot="{ close }" classes="flex justify-center items-center" content-class="bg-white px-[77px] pt-[52px] pb-[72px] rounded-sm w-[546px] " :keep-overlay="true" :click-to-close="false">
    <h1 class="text-[20px] text-heading leading-6 font-semibold">
      {{ $t(title) }}
    </h1>
    <form class="rounded-lg mt-[45px] flex gap-[30px] flex-col" @submit.prevent="() => submit(close)">
      <div class="flex flex-col">
        <h2 class="label text-16 font-semibold font-inter text-input mb-[10px]">{{ $t('Appointment ID') }}*</h2>
        <p class="text-value text-16 font-inter leading-[19px]">
          {{ prefix_id ?? 'TM00001' }}
        </p>
      </div>

      <div class="flex">
        <SingleDatePicker label="Appointment Date" :asterisk="true" v-model="form.appointment_date" :min_date="new Date(dayjs().add(1, 'day'))" :highlight_today="false" />
      </div>

      <div class="flex flex-col">
        <h2 class="label text-16 font-semibold font-inter text-input mb-[10px]">{{ $t('Appointment Time') }}*</h2>
        <div class="relative w-full">
          <input
            type="text"
            class="border border-input h-10 rounded-lg outline-none p-3 text-input text-16 w-full"
            v-model="form.appointment_time"
            placeholder="HH:MM"
            :class="{ 'border-error': v$.appointment_time.$errors.length }"
            @input="v$.appointment_time.$touch()"
          />
          <ClockIcon class="absolute top-[50%] right-[11px] translate-y-[-50%] pointer-events-none" />
        </div>
      </div>

      <div class="flex flex-col">
        <h2 class="label text-16 font-semibold font-inter text-input mb-[10px]">{{ $t('Comments') }}</h2>
        <textarea class="text-value text-16 font-inter border border-input rounded-lg min-h-[100px] resize-none p-3 outline-none" :placeholder="$t('Record remarks')" v-model="form.notes"> </textarea>
      </div>

      <div class="control-btns flex gap-[18px] mt-[44px]">
        <GradientButton class="min-w-[187px] h-12" type="submit">{{ $t('Save') }}</GradientButton>
        <WhiteButton class="min-w-[187px] h-12" type="button" @click="showCancelModal(close)">{{ $t('Cancel') }}</WhiteButton>
      </div>
    </form>
  </vue-final-modal>
</template>

<script setup>
import { Form as vForm } from 'vform'
import { inject } from '@vue/runtime-core'
import useVuelidate from '@vuelidate/core'
import GradientButton from '@/components/button/Gradient.vue'
import WhiteButton from '@/components/button/White.vue'
import SingleDatePicker from '@/components/form/SingleDatePicker.vue'
import ClockIcon from '@/components/icons/Clock.vue'
import dayjs from 'dayjs'
import { helpers, required } from '@vuelidate/validators'
import axios from 'axios'
import { ref, onMounted, reactive } from 'vue'

const $vfm = inject('$vfm')
const emit = defineEmits(['created'])
const props = defineProps({
  title: {
    type: String,
    default: 'Enter new appointment',
    required: false,
  },
  contact_data_record_id: {
    type: [Number, String],
    required: true,
  },
})

const prefix_id = ref()
const form = reactive(
  new vForm({
    appointment_date: '',
    appointment_time: '',
    notes: '',
  })
)

const rules = {
  appointment_date: {
    required,
    date_is_future: (value) => {
      return dayjs(value).isAfter(dayjs())
    },
  },

  appointment_time: {
    required,
    valid_format: helpers.regex(/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/),
  },
}

const v$ = useVuelidate(rules, form)

const submit = async (close) => {
  if (v$.value.$invalid) {
    return $vfm.show('field-missing')
  }

  $vfm.show('confirmation', {
    title: 'Save appointment and continue workflow?',
    description:
      'If you save this appointment, the contact record workflow is automatically continued according to the defined process flow. Are you sure that you really want to save this appointment and thus continue the workflow?',
    yesClick: async () => {
      const { data } = await form.post(route('appointment.store', { contact_data_record: props.contact_data_record_id }))
      emit('created', data)
      close()
    },
  })
}

const showCancelModal = (close) => {
  $vfm.show('confirmation', {
    title: 'Cancel appointment entry?',
    description: 'If you cancel this process, all data will be discarded. Are you sure you really want to cancel this appointment entry?',
    yesClick: close,
  })
}

const getNextPrefixID = async () => {
  try {
    const res = await axios.get(route('next-prefix-id', { modelName: 'ContactDataRecordAppointment', contact_data_record_id: props.contact_data_record_id }))
    prefix_id.value = res.data
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  getNextPrefixID()
})
</script>

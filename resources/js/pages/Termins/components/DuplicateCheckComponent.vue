<template>
  <DetailsContainer :title="title">
    <template v-for="(duplicate, index) in is_duplicate" :key="duplicate.prefix_id">
      <div class="flex flex-col gap-3">
        <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Possible duplicate') }}</h4>
        <a class="text-16 text-[#13A3E5] self-start" :href="$router.resolve({ name: 'contact-data-records-show', params: { id: duplicate.contact_data_record_id } }).href" target="_blank"> {{ duplicate.prefix_id }}</a>
      </div>

      <div class="flex flex-col gap-3">
        <RadioInput label="Examination result" labelClass="whitespace-nowrap" v-model="duplicate.contact_record_status" :options="options" :asterisk="true" />
      </div>

      <div class="spacer"></div>
    </template>

    <div class="button col-span-3">
      <GradientButton
        @click="updateContactDataRecordsDuplicateStatus"
        class="w-[160px] mt-[48px] disabled:from-[#BBBBBB] disabled:to-[#BBBBBB]"
        :disabled="!is_duplicate.some((dp) => dp.contact_record_status == 'New' || dp.contact_record_status == 'Duplicate')"
        >{{ $t('Confirm') }}</GradientButton
      >
    </div>
  </DetailsContainer>
</template>

<script setup>
import { ref } from 'vue'
import DetailsContainer from './DetailsContainer.vue'
import RadioInput from '@/components/form/RadioInputs.vue'
import GradientButton from '@/components/button/Gradient.vue'
import { notificationShowStore } from '@/store/notification.js'
import axios from 'axios'

const emit = defineEmits(['updateContactDataRecord'])
const props = defineProps({
  title: {
    type: String,
    default: 'Duplicate check',
  },
  duplicates: {
    type: Array,
    required: true,
  },
  contact_data_record_id: {
    type: [String, Number],
    required: true,
  },
})

const notification = notificationShowStore()
const is_duplicate = ref(props.duplicates.map((dp) => ({ contact_data_record_id: dp.id, contact_record_status: dp.contact_record_status == 'Duplicate' ? 'Duplicate' : null, prefix_id: dp.prefix_id })))

const options = [
  { value: 'New', label: 'No duplicate' },
  { value: 'Duplicate', label: 'duplicate' },
]

const updateContactDataRecordsDuplicateStatus = async () => {
  try {
    const data = is_duplicate.value.filter((dp) => dp.contact_record_status == 'Duplicate' || dp.contact_record_status == 'New').map(({ prefix_id, ...rest }) => rest)
    const res = await axios.put(route('contact-data-records.duplicate-check', { contact_data_record: props.contact_data_record_id }), { duplicate_records: data })

    const not_duplicate = data.filter((dp) => dp.contact_record_status == 'New').map(({ contact_data_record_id }) => contact_data_record_id)
    is_duplicate.value = is_duplicate.value.filter(({ contact_data_record_id }) => not_duplicate.every((id) => id != contact_data_record_id))
    emit('updateContactDataRecord')
    notification.success('The duplicate check was successfully saved')
  } catch (error) {
    console.log(error)
  }
}
</script>

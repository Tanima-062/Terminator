<template>
  <div class="flex gap-[81px]">
    <div class="days flex flex-col pt-[34px]">
      <p v-for="day in days" :key="day" class="text-[#636363] font-roboto text-16 leading-[19px] h-[34px] flex items-center">{{ $t(day) }}</p>
    </div>
    <div class="times">
      <div class="header flex gap-5 text-white">
        <p class="w-[125px] h-[34px] bg-[#AB326F] rounded-t-[6px] py-[6px] px-[12px] text-[16px] leading-6 font-[600] font-roboto">{{ $t('From') }}</p>
        <p class="w-[125px] h-[34px] bg-[#AB326F] rounded-t-[6px] py-[6px] px-[12px] text-[16px] leading-6 font-[600] font-roboto">{{ $t('To') }}</p>
        <p class="w-[125px] h-[34px] bg-[#AB326F] rounded-t-[6px] py-[6px] px-[12px] text-[16px] leading-6 font-[600] font-roboto">{{ $t('From') }}</p>
        <p class="w-[125px] h-[34px] bg-[#AB326F] rounded-t-[6px] py-[6px] px-[12px] text-[16px] leading-6 font-[600] font-roboto">{{ $t('To') }}</p>
      </div>
      <div class="body grid grid-cols-4 gap-x-5 text-[#636363] font-roboto text-[16px] leading-6 divide-y divide-input">
        <template v-for="(day, index) in days" :key="day">
          <input v-for="time in ['first_start_time', 'first_end_time', 'last_start_time', 'last_end_time']" class="w-[125px] h-[34px] border-x border-x-input py-[6px] px-[12px]" :class="{'!border-b border-b-input rounded-b-[6px]': index == days.length - 1}" :value="parseTime(day, time)" :disabled="true" />
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

let days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
  availability: {
    type: Array,
    requried: true,
  },
})

const parseTime = (day, time) => {
  let input = props.availability.find((item) => item.day == day) ? props.availability.find((item) => item.day == day)[time] : ''
  input = input?.split(':')
  input?.pop()
  return input?.join(':')
}
</script>

<template>
  <SingleSelect :options="translatedCountries ?? []" :placeholder="$attrs.placeholder ?? 'Select Country'" :searchable="true" :searchables="['label', 'dialCode']">
    <template #option="{ option }">
      <div
        class="option flex px-[12px] py-[6px] hover:bg-heading hover:text-white cursor-pointer"
        :class="[option.value === ($attrs.modelValue && $attrs.modelValue.toLowerCase()) ? 'bg-heading text-white' : 'text-[#636363]']"
        @click="
          () => {
            option.toggle()
            $emit('update:modelValue', option.value)
          }
        "
      >
        <div class="wrapper flex w-6 justify-center items-center mr-3">
          <div class="default-flat iti-flag" :style="{ backgroundImage: `url(${FlagImage})` }" :class="`${option.value.toLowerCase()}`"></div>
        </div>
        <div class="dialcode">
          {{ option.additionals.dialCode }}
        </div>
        <div class="name ml-auto">{{ option.label }}</div>
      </div>
    </template>
  </SingleSelect>
</template>

<script setup>
import SingleSelect from './SingleSelect.vue'
import { useGetCountryName } from '@/composables/translation.js'
import countries from '@/countries.json'
import FlagImage from '@image/flags.png'
import { computed } from '@vue/reactivity'

const translatedCountries = computed(() => {
  return countries.map((country) => ({
    value: country.iso,
    label: useGetCountryName(country.iso),
    dialCode: country.dialCode,
    original_name: country.name,
  }))
})
</script>

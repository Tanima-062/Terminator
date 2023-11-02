<template>
  <div class="wrapper">
    <template v-if="label">
      <label class="text-input block mb-3 text-formLabel" :class="attrs.labelClass">
        <template v-if="typeof $attrs.label == 'object'">
          {{ $t(label.text, label.replace) }}
        </template>
        <template v-else> {{ $t(label) }}{{ $attrs.asterisk ? '*' : '' }} </template>
      </label>
    </template>
    <div class="w-full relative h-max" v-bind="attrs" v-click-away="() => (expand = false)">
      <div class="flex h-10 w-full bg-white rounded-[8px] gap-6 items-center px-3 border border-input" :class="[is_valid === false || error ? '!border-error' : '', expand ? 'rounded-b-[0px]' : '', attrs.class]">
        <div class="country-code-toggler w-max" @click="expand = true">
          <div v-if="!expand && selected_country" class="selected-code flex gap-3 items-center justify-center text-input">
            <div class="default-flat iti-flag" :style="{ backgroundImage: `url(${FlagImage})` }" :class="selected_country.value"></div>
            <div>+{{ selected_country.dialCode }}</div>
          </div>

          <input v-else class="outline-none text-input placeholder-bodyText/60 leading-[32px] h-full text-[16px] w-[60px]" type="text" v-model="search" :placeholder="attrs.code_placeholder ?? $t('Search')" />
        </div>

        <div class="separator text-[#E6DEE5]">|</div>

        <input
          class="bg-transparent outline-none text-input text-[16px] placeholder-bodyText/60 leading-[32px] w-full"
          :class="attrs.phoneNumberClass"
          type="text"
          v-model="number"
          :placeholder="attrs.number_placeholder ?? '111111111'"
          :disabled="!selected_country"
        />
      </div>

      <div class="country-codes absolute z-10 bg-white flex flex-col w-full top-[99%] rounded-b-lg border-b-[1px] border-x-[1px] border-input max-h-[150px] overflow-y-auto" v-if="expand">
        <div
          v-for="(country, index) in filtered_countries"
          :key="index"
          class="country-code flex px-[12px] py-[6px] hover:bg-heading hover:text-white cursor-pointer"
          :class="[selected_country?.value == country.value ? 'bg-heading text-white' : 'text-[#636363]']"
          @click="
            () => {
              selected_country = country.value
              toggle()
            }
          "
        >
          <div class="wrapper flex w-6 justify-center items-center mr-3">
            <div class="default-flat iti-flag" :style="{ backgroundImage: `url(${FlagImage})` }" :class="`${country.value}`"></div>
          </div>
          <div class="dialcode">+{{ country.dialCode }}</div>
          <div class="name ml-auto">{{ country.label }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useGetCountryName } from '@/composables/translation.js'
import countries from '@/countries.json'
import FlagImage from '@image/flags.png'
import { computed, ref } from '@vue/reactivity'
import { useAttrs } from 'vue'

const translatedCountries = computed(() => {
  return countries.map((country) => ({
    value: country.iso,
    label: useGetCountryName(country.iso),
    dialCode: country.dialCode,
    original_name: country.name,
  }))
})

const filtered_countries = computed(() => {
  if (!translatedCountries.value) return []
  if (!search.value) return translatedCountries.value

  const searchables = ['label', 'dialCode']
  return translatedCountries.value.filter((country) => searchables.some((searchKey) => country[searchKey].toLowerCase().startsWith(search.value.toLowerCase())))
})

const attrs = useAttrs()
const emit = defineEmits(['update:country_code', 'updated', 'update:phone_number'])

const props = defineProps({
  error: [String, Boolean],
  label: String,
  lang: {
    type: String,
    required: false,
  },
})
const search = ref('')

const expand = ref(false)
const toggle = () => (expand.value = !expand.value)

const is_valid = computed(() => {
  if (!attrs.country_code || !attrs.phone_number) return undefined
  return /^\d*$/.test(attrs.phone_number) && attrs.phone_number?.length >= 4 && attrs.phone_number?.length <= 15
})

const selected_country = computed({
  get: () => translatedCountries.value?.find((country) => country.value == attrs.country_code?.toLowerCase()),
  set: (value) => {
    emit('update:country_code', value)
    emit('updated', value)
  },
})

const number = computed({
  get: function () {
    return attrs.phone_number
  },
  set: function (value) {
    emit('update:phone_number', value)
    emit('updated', value)
  },
})
</script>

<script>
export default {
  inheritAttrs: false,
}
</script>

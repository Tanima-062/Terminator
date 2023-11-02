<template>
  <div class="input-element w-full" :class="$attrs.wrapperClass">
    <label :for="$attrs.id" class="text-value block mb-3 text-formLabel font-[500]" :class="$attrs.labelClass" v-if="label" v-trans="label"></label>
    <div class="input relative h-10">
      <input
        class="absolute left-2 w-[280px] h-full  text-[#585858] placeholder-bodyText/60 leading-[32px] border border-input rounded-[8px] bg-white outline-none pr-3 pl-[60px]"
        :class="{ 'border-error': error }"
        v-bind="$attrs"
        :placeholder="typeof $attrs.placeholder == 'object' ? $t($attrs.placeholder.text, $attrs.placeholder.replace) : $t($attrs.placeholder ?? '')"
        :value="$attrs.modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
      <div class="absolute top-[50%] translate-y-[-50%] text-16 font-[500] text-[#555555] bg-[#E6DEE5] h-full left-0 rounded-l-[8px] w-[53px] flex items-center justify-center">CHF</div>
    </div>
    <p class="error-box flex p-0 items-center gap-2 mt-[4px] text-error text-12" v-if="typeof error == 'string' && error"><ErrorCrossIcon /> {{ $t(error) }}</p>
  </div>
</template>

<script setup>
import ErrorCrossIcon from '@/components/icons/ErrorCross.vue'
defineProps({
  label: {
    type: [String, Object],
    required: false,
    default: '',
  },
  error: {
    type: [Boolean, String],
    required: false,
  },
})
</script>

<script>
export default {
  inheritAttrs: false,
}
</script>

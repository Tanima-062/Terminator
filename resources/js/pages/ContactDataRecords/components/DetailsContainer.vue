<template>
  <div class="flex flex-col rounded-[8px]">
    <button class="collpase-btn flex justify-between items-center border border-input bg-body h-[51px] pl-[30px] pr-[23px]" @click="show = !show" :class="$attrs.headerClass">
      <span class="text-heading text-[20px] leading-6 font-semibold">{{ $t(title) }}</span>
      <component :is="show ? UpArrowIcon : DownArrowIcon"></component>
    </button>

      <div class="content-container bg-white border border-input shadow-[1px_1px_14px_rgba(89,_89,_89,_0.1)] grid grid-cols-3 gap-y-11 pt-[33px] pb-[70px] px-[30px]" :class="[$attrs.contaienrClass]" v-if="show">
        <slot>
          <div class="content flex flex-col gap-3" v-for="(value, key) in contents">
            <slot name="content" :value="value" :key="key" :translations_required="translations_required">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ translations_required.includes(key) ? $t(value ? `${value}` : '') : value }}</p>
            </slot>
          </div>
        </slot>
      </div>
  </div>
</template>

<script setup>
import UpArrowIcon from '@/components/icons/UpArrowIcon.vue'
import DownArrowIcon from '@/components/icons/DownArrowIcon.vue'
import { ref } from '@vue/reactivity'

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  contents: {
    type: Object,
    required: false,
  },
  translations_required: {
    type: Array,
    default: () => [],
  },
})

const show = ref(true)
</script>

<template>
  <div class="tooltip relative group">
    <Pill :length="items.length" />
    <div
      class="tooltip group-hover:visible invisible absolute top-[120%] z-20 w-max bg-white flex flex-col border border-input px-[10px] py-[9px] gap-[10px] overflow-y-auto max-h-[200px] rounded-[5px] shadow-[1px_1px_7px_rgba(114,_114,_114,_0.25)] transition-all duration-100 ease-linear"
    >
      <template v-for="(item, index) in items" :key="index">
        <slot name="item" :item="item">
          <p class="label flex gap-[10px] items-center text-[#636363] text-4 leading-4">
            {{ item }}
          </p>
        </slot>
      </template>
    </div>
  </div>
</template>

<script setup>
import Pill from '@/components/utils/Pill.vue'

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
})
</script>

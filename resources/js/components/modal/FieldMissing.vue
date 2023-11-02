<template>
  <vue-final-modal v-model="show" name="field-missing" classes="flex justify-center items-center" content-class="bg-white px-[31px] pt-11 pb-[38px] rounded-sm w-[621px]" :click-to-close="false" v-slot="{ close }">
    <h2 class="text-2xl text-heading font-semibold font-poppins mb-[21px] text-center">
      {{ $t(`Missing or invalid information`) }}
    </h2>

    <p class="text-[#5F4C5C] text-[20px] leading-6 mb-8 font-normal text-center">
      {{ $t(`Please fill in all mandatory fields and or record them in the required valid format.`, { slash: '/' }) }}
    </p>

    <ButtonGradient @click="close" class="w-[127px] h-[48px] m-auto">Ok</ButtonGradient>
  </vue-final-modal>
</template>

<script setup>
import { ref } from '@vue/reactivity'
import ButtonGradient from '@/components/button/Gradient.vue'
const show = ref(false)
</script>

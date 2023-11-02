<template>
  <vue-final-modal
    v-model="show"
    name="success-notification"
    classes="flex justify-center items-start mt-[25px] "
    content-class="bg-white pb-[32px] pl-[54px] pt-[20px] pr-[20px] rounded-sm w-[665px] notification-modal"
    :click-to-close="false"
    v-slot="{ close, params }"
    :hide-overlay="true"
    @before-open="beforeOpen"
  >
    <template v-if="params">
      <div class="header">
        <div class="close flex justify-end">
          <svg class="cursor-pointer" @click="close" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 1L1 11M1 1L11 11" stroke="#AB326F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
      </div>
      <h2 class="text-heading font-semibold font-poppins mb-[12px]" style="font-size: 20px; line-height: 30px">
        {{ $t(params['title'] ?? 'Notification') }}
      </h2>

      <p class="text-[#5F4C5C] text-16 font-normal" v-trans="params.description ?? ''"></p>
    </template>
  </vue-final-modal>
</template>

<script setup>
import { ref } from '@vue/reactivity'
import ButtonGradient from '@/components/button/Gradient.vue'
import ButtonWhite from '@/components/button/White.vue'
const show = ref(false)

const beforeOpen = (e) => {
  const timeout = e.ref.params.value.duration ?? 3000
  setTimeout(() => {
    show.value = false
  }, timeout)
}
</script>

<style lang="scss">
.notification-modal {
  filter: drop-shadow(2px 1px 33px rgba(0, 0, 0, 0.25));
}
</style>

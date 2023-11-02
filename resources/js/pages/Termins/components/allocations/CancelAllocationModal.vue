<template>
    <vue-final-modal
      v-model="show"
      name="cancel-allocation-modal"
      classes="flex justify-center items-center"
      content-class="bg-white px-[31px] pt-11 pb-[38px] rounded-sm w-[647px]"
      :click-to-close="false"
      v-slot="{ close, params: { title, description } }"
    >
      <h2 class="text-2xl text-heading font-semibold font-poppins mb-[22px] text-center">
        {{ $t(title ?? "Cancel Lead Distribution?") }}
      </h2>

      <p class="text-[#5F4C5C] text-[20px] leading-6 mb-13 font-normal text-center">
        {{ $t(description ?? "Are you sure you really want to cancel the lead distribution?") }}
      </p>

      <div class="flex items-start justify-center gap-4 mt-16">
        <ButtonGradient @click="ok(close)" class="w-[127px] h-[48px]">
          {{ $t("Yes") }}
        </ButtonGradient>

        <ButtonWhite @click="close" class="w-[127px] h-[48px]">
          {{ $t("No") }}
        </ButtonWhite>
      </div>
    </vue-final-modal>
  </template>

  <script setup>
  import {inject} from 'vue'
  import { ref } from "@vue/reactivity";
  import ButtonGradient from "@/components/button/Gradient.vue";
  import ButtonWhite from "@/components/button/White.vue";
  import { useRouter } from "vue-router";
  const show = ref(false);
  const $vfm = inject("$vfm");

    const ok = (close) => {
        $vfm.hide("allocation-modal");
        close();
    }

  </script>

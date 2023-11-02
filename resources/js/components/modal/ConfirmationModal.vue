<template>
  <vue-final-modal
    v-model="show"
    name="confirmation"
    classes="flex justify-center items-center"
    content-class="bg-white pt-11 px-[51px] pb-[38px] rounded-sm w-[647px]"
    :click-to-close="false"
    v-slot="{ close, params}"
  >
    <template v-if="params">
      <h2
        class="text-2xl text-heading font-semibold font-poppins mb-[15px] text-center"
        v-if="params.title"
        v-trans="params.title"
      ></h2>

      <!-- v-trans="params.title" -->
      <p
        class="text-[#5F4C5C] text-[20px] leading-6 font-normal text-center"
        v-if="params.description"
        v-trans="params.description"
      ></p>
      <!-- v-trans="params.description" -->

      <div class="footer flex items-start justify-center gap-4 mt-16">
        <ButtonGradient
          @click="
            () => {
              params.yesClick();
              close();
            }
          "
          class="w-[127px] h-[48px]"
        >
          {{ params.yesText? $t(params.yesText) : $t("Ok") }}
        </ButtonGradient>
        <ButtonWhite @click="close" class="w-[127px] h-[48px]">
          {{ params.noText? $t(params.noText) : $t("No") }}
        </ButtonWhite>
      </div>
    </template>
  </vue-final-modal>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import ButtonGradient from "@/components/button/Gradient.vue";
import ButtonWhite from "@/components/button/White.vue";
const show = ref(false);
</script>

<template>
  <vue-final-modal
    v-model="show"
    name="redirect-modal"
    classes="flex justify-center items-center"
    content-class="bg-white px-[31px] pt-11 pb-[38px] rounded-sm w-[647px]"
    :click-to-close="false"
    v-slot="{ close, params: { title, description, target } }"
  >
    <h2 class="text-2xl text-heading font-semibold font-poppins mb-[22px] text-center">
      {{ $t(title ?? "") }}
    </h2>

    <p class="text-[#5F4C5C] text-[20px] leading-6 mb-13 font-normal text-center">
      {{ $t(description ?? "") }}
    </p>

    <div class="flex items-start justify-center gap-4 mt-16">
      <ButtonGradient @click="() => redirect(target, close)" class="w-[127px] h-[48px]">
        {{ $t("Yes") }}
      </ButtonGradient>

      <ButtonWhite @click="close" class="w-[127px] h-[48px]">
        {{ $t("No") }}
      </ButtonWhite>
    </div>
  </vue-final-modal>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import ButtonGradient from "@/components/button/Gradient.vue";
import ButtonWhite from "@/components/button/White.vue";
import { useRouter } from "vue-router";
const show = ref(false);
const router = useRouter();
const redirect = (target = undefined, close) => {
  !target ? router.back() : router.push(target);
  close();
};

</script>

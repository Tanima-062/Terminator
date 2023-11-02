<template>
    <vue-final-modal
    v-model="show"
    name="email-exist-alert"
    classes="flex justify-center items-center"
    content-class="bg-white px-[31px] pt-11 pb-[38px] rounded-sm w-[621px]"
    :click-to-close="false"
    v-slot="{ close, params }">
        <template v-if="params">
            <h2 class="text-2xl text-heading font-semibold font-poppins mb-[21px] text-center">
                {{ $t(params['title'] ?? 'Alert Message') }}
            </h2>

            <p class="text-[#5F4C5C] text-[20px] leading-6 mb-8 font-normal text-center">
                {{ $t(params.description ?? '') }}
            </p>
            <ButtonGradient @click="close" class="w-[127px] h-[48px] m-auto">
                {{ "Ok" }}
            </ButtonGradient>
        </template>
    </vue-final-modal>
</template>

<script setup>
import { ref } from "@vue/reactivity";
import ButtonGradient from "@/components/button/Gradient.vue";

const show = ref(false);

</script>


<style lang="scss">
.notification-modal {
    filter: drop-shadow(2px 1px 33px rgba(0, 0, 0, 0.25));
}
</style>

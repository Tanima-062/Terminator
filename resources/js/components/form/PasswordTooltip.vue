<template>
    <div class="password-tooltip relative flex items-center" v-click-away="() => show = false">

        <svg @click="toggle" class="z-[0] left-[13px]" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="6" cy="6" r="6" fill="#8B387F" />
            <rect x="5.2793" y="4.99219" width="1.344" height="4.704" fill="white" />
            <circle cx="5.99959" cy="3.21444" r="0.816" fill="white" />
        </svg>


        <div v-show="show" class="absolute w-[250px]  shadow-xl top-[10px] left-[-6px] pr-[5px] pb-[15px] rounded-[10px] rounded-tl-[0px] bg-violet-1 z-[1]  bg-white  border border-solid border-[#E6DEE5]">
            <div class="w-full pt-1 pl-[6px]">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="6" cy="6" r="5.5" stroke="#8A397E" />
                        <rect x="5.2793" y="4.99219" width="1.344" height="4.704" fill="#8A397E" />
                        <circle cx="5.99959" cy="3.21444" r="0.816" fill="#8A397E" />
                </svg>
            </div>
            <div class="w-full pl-[25px] items-center rounded-r-3xl">
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]"/><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('Not previously used password') }}</span></p>
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]" /><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('At least 8 characters') }}</span></p>
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]" /><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('At least 1 upper case letter') }}</span></p>
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]" /><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('At least 1 lower case letter') }}</span></p>
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]" /><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('At least one special character') }}</span></p>
                <p class="w-full inline-flex"><InfoIcon class="align-middle mr-[6px]" /><span class="font-normal font-lato text-textGray text-[13px] leading-[10px]">{{ $t('At least one number') }}</span></p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import InfoIcon from "@/components/icons/InfoIcon.vue"

const show = ref(false);
const toggle = () => show.value = !show.value
</script>

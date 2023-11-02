<template>
    <div class="h-screen bg-body flex justify-center items-center xs:px-[10px]">
        <div
            class="w-588  bg-white rounded-sm px-[80px] xs:px-[20px] pb-[75px] xs:pb-[10px] pt-[40px] xs-pt[15px] m-auto shadow-xl shadow-#1f2937-800/25">
            <!-- <img src="../assets/img/logo.png" alt="logo" class="mx-auto xs:w-[80%]" /> -->
            <Logo />
            <slot></slot>
        </div>
    </div>
    <Notification />
</template>

<script setup>
import { inject, onMounted, ref, onBeforeMount } from 'vue';
import Logo from '../components/icons/Logo.vue';
import {loadLanguageAsync} from 'laravel-vue-i18n'
import { isLoaded } from 'laravel-vue-i18n';
import { useRouter, useRoute } from 'vue-router'
import Notification from "@/components/modal/Notification.vue";
import { storeToRefs } from "pinia";
import { notificationShowStore } from "@/store/notification";
import { watch } from "@vue/runtime-core";

const route_param = useRoute();
const $vfm = inject("$vfm")
let description = ref('')

let notificationStore = notificationShowStore();
let { notification } = storeToRefs(notificationStore);

onBeforeMount(() => {
    description.value = notification.value?.description ?? ''
})

onMounted(()=> {

    let lang = 'de';
    if (description.value) {
        $vfm.show("success-notification", {
            description: description.value,
        });
        notificationStore.clear();
    }

    // reset password user language check
    if (route_param.query.lang) {
        lang = route_param.query.lang;
    }
    let cacheLanguage = localStorage.getItem('lang');

    // lang = usePage().props.value.language_code ?? (cacheLanguage ?? lang) ;
    localStorage.setItem('lang', lang);
    loadLanguageAsync(lang);

})
</script>

<style lang="scss" scoped>

</style>

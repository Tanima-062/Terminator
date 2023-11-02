<template>
    <div
      class=""
    >
      <slot> </slot>
    </div>
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

    onMounted(()=> {

        let lang = 'en';

        // reset password user language check
        if (route_param.query.lang) {
            lang = route_param.query.lang;
        }

        localStorage.setItem('lang', lang);
        loadLanguageAsync(lang);

    })
</script>

<style lang="scss" scoped>

</style>

<template>
    <div
        class="bg-body grid grid-cols-[90px,_auto] md:grid-cols-[305px,_1fr] grid-rows-[90px,_minmax(calc(100vh_-_90px), max-content)] gap-y-5"
        :class="{ '!grid-cols-[90px,_auto]': navbarCollapse }"
    >
        <Navbar
            @toggleNavbar="navbarCollapse = !navbarCollapse"
            :collapse="navbarCollapse"
        />
        <Sidebar :collapse="navbarCollapse" />
        <router-view> </router-view>
    </div>

    <!-- Some Common Modals -->
    <FieldMissingModal />
    <ConfirmationModal />
    <Notification />
    <EmailExistAlert />
    <RedirectModal />
</template>

<script setup>
import { inject, onMounted, ref } from "vue";
import { loadLanguageAsync } from "laravel-vue-i18n";
import Navbar from "./components/Navbar.vue";
import Sidebar from "./components/Sidebar.vue";
import FieldMissingModal from "@/components/modal/FieldMissing.vue";
import ConfirmationModal from "@/components/modal/ConfirmationModal.vue";
import Notification from "@/components/modal/Notification.vue";
import EmailExistAlert from "@/components/modal/EmailExistAlert.vue";
import RedirectModal from "../components/modal/RedirectModal.vue";
import { storeToRefs } from "pinia";
import { notificationShowStore } from "@/store/notification";
import { watch } from "@vue/runtime-core";
import { useUserStore } from "@/store/user";
import { companyRolesStore } from "@/store/company_roles";
import { languageStore } from "@/store/language";

const navbarCollapse = ref(false);
const $vfm = inject("$vfm");
let notificationStore = notificationShowStore();
let { notification } = storeToRefs(notificationStore);

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const { fetchLanguage } = languageStore();
fetchLanguage();

// get company role from store
const { fetchCompanyRole } = companyRolesStore();
fetchCompanyRole();

watch(notification, () => {
    $vfm.show("success-notification", {
        description: notification.value.description,
    });
});

onMounted(() => {
    const lang = user.value.language?.code || "en"; //need work here
    localStorage.setItem("lang", lang);
    loadLanguageAsync(lang);
});
</script>

<style lang="scss" scoped></style>

<template>
    <div class="table__menubar" v-click-away="() => (openDropdownMenu = false)">
        <svg
            class="bar-icon"
            width="18"
            height="12"
            viewBox="0 0 18 12"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            @click="openDropdownMenu = !openDropdownMenu"
        >
            <path
                d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z"
                fill="#636363"
            />
        </svg>
        <ul
            class="table__menubar__dropdown min-w-[170px]"
            v-if="openDropdownMenu"
        >
            <li>
                <a @click.prevent="showLog('inactive')">
                    {{ $t("View status history") }}</a
                >
            </li>
        </ul>
    </div>
</template>

<script setup>
import { trans } from "laravel-vue-i18n";
import { inject } from "@vue/runtime-core";
import { ref } from "@vue/reactivity";
import CompetenceLogModal from "./CompetenceLogModal.vue";

const props = defineProps({
    competence_log: {
        type: Object,
        required: true,
    },
});
const $vfm = inject("$vfm");
let openDropdownMenu = ref(false);

const showLog = () => {
    const options = {
        component: CompetenceLogModal,
        bind: {
            title: trans("Status history"),
            competence_log: props.competence_log,
        },
    };
    $vfm.show(options);
};
</script>

<style lang="scss" scoped></style>

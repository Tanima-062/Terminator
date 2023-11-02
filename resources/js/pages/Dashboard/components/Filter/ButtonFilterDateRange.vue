<template>
    <div
        class="filter__button relative"
        :class="{ 'filter__button--open': expanded }"
    >
        <button
            class="filter__button--close cursor-pointer flex justify-center items-center w-full font-inter text-[18px] text-center rounded-[10px] px-[10px] py-[5px] font-normal"
            :class="{
                'text-[#FFFFFF]': expanded,
                'text-[#636363]': !expanded,
            }"
            @click="
                () => {
                    expanded = !expanded;
                }
            "
        >
            <span :class="{'pr-2':dateRange.start}">{{ $t($attrs.label ?? "") }}</span>
            <div
                class="border-l-2 border-[#C0C0C0] pl-2 flex"
                v-if="dateRange.start"
                >{{ formateDate(dateRange.start) }}<span class="middle"></span
                >{{ formateDate(dateRange.end) }}</div
            >
        </button>
        <div
            class="wrapper relative"
            v-if="expanded"
            v-click-away="
                () => {
                    expanded = false;
                    openFilterMenu = false;
                }
            "
        >
            <div
                class="filter__menu options absolute z-10 bg-white flex flex-col w-[481px] top-[100%] rounded-b-lg border-[1px] border-x-[1px] border-input h-[425px] overflow-y-auto"
                v-if="expanded"
            >
                <div class="filter__menu--header mb-8 flex justify-end">
                    <slot name="close">
                        <svg
                            class="cursor-pointer"
                            width="14"
                            height="14"
                            viewBox="0 0 14 14"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            @click="expanded = false"
                        >
                            <path
                                d="M13 1L1 13M1 1L13 13"
                                stroke="#AB326F"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </slot>
                </div>

                <div class="filter__menu--body flex">
                    <div
                        class="border-r-2 border-[#C0C0C0] w-[185.5px] h-[310px]"
                    >
                        <div
                            class="border-b-2 border-[#C0C0C0] text-[#555555] text-center pt-5 pl-5 pr-5 pb-5 font-inter font-[16px]"
                        >
                            <h3>{{ "Benutzerdefiniert" }}</h3>
                        </div>
                        <div class="mt-5">
                            <div
                                class="daterange_leftlabel"
                                @click="rangeDays(6)"
                            >
                                <h3 class="pt-1 pl-3">
                                    {{ $t("Last 7 Days") }}
                                </h3>
                            </div>
                            <div
                                class="daterange_leftlabel"
                                @click="rangeDays(13)"
                            >
                                <h3 class="pt-1 pl-3">
                                    {{ $t("Last 14 Days") }}
                                </h3>
                            </div>
                            <div
                                class="daterange_leftlabel"
                                @click="rangeDays(30)"
                            >
                                <h3 class="pt-1 pl-3">
                                    {{ $t("Last 30 Days") }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="ml-5">
                        <div>
                            <DateRangeSelector
                                v-model:start_date="dateRange.start"
                                v-model:end_date="dateRange.end"
                                class="mb-[30px]"
                            />
                        </div>
                        <div
                            class="filter__menu--footer flex w-full gap-3 mt-[200px]"
                        >
                            <button
                                class="filter__apply--button font-inter text-white text-center rounded-[10px] px-[30px] py-[12px] font-medium w-1/2"
                                @click="apply"
                            >
                                {{ $t("Apply") }}
                            </button>
                            <button
                                :class="`filter__reset--button font-inter text-white text-center rounded-[10px] ${
                                    reset == 'Reset' ? 'px[30px]' : 'px[15px]'
                                } py-[12px] font-medium  w-1/2`"
                                @click="reset"
                            >
                                <div>{{ $t("Reset") }}</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from "@vue/reactivity";
import Checkbox from "@/components/utils/Checkbox.vue";
import { useAttrs } from "vue";
import { debounce } from "lodash";
import DateRangeSelector from "./DateRangeSelector.vue";
import { computed, onMounted, watch } from "@vue/runtime-core";
import dayjs from "dayjs";

const emit = defineEmits(["update:modelValue", "opened", "onApply", "reset"]);
let openFilterMenu = ref(false);
const attrs = useAttrs();
let expanded = ref(false);

const props = defineProps({
    start_date: {
        type: String,
        default: null,
    },
    end_date: {
        type: String,
        default: null,
    },
});

const dateRange = reactive({
    start: null,
    end: null,
});

const rangeDays = (range) => {
    const now = new Date();
    dateRange.start = dayjs(new Date()).format("YYYY-MM-DD");
    dateRange.end = dayjs(
        new Date(now.getFullYear(), now.getMonth(), now.getDate() - range)
    ).format("YYYY-MM-DD");
};

const apply = () => {
    expanded.value = !expanded;
    emit("onApply", { start_date: dateRange.start, end_date: dateRange.end });
};
const reset = () => {
    dateRange.start = null;
    dateRange.end = null;
    expanded.value = !expanded;
    emit("reset");
};

onMounted(() => {
    if (props.start_date && props.end_date) {
        dateRange.start = props.start_date;
        dateRange.end = props.end_date;
    }
});
</script>

<style lang="scss" scoped>
.filter__button {
    border-radius: 10px;
    font-size: 16px;
    line-height: 19px;
    border: 1px solid #e6dee5;
}
.text_color {
    color: #636363;
}

.filter__button--open {
    background: linear-gradient(
        180deg,
        #3f8aa7 -7.29%,
        #76b5ce 0.72%,
        #67a7c0 7.93%,
        #5a9fb9 14.08%,
        #5398b4 20.71%,
        #3d89a6 28.48%,
        #3181a0 34.37%,
        #3281a0 34.56%,
        #297c9c 108.33%
    );
    border-radius: 10px 10px 0px 0px;
}

.filter__menu {
    box-sizing: border-box;
    position: absolute;
    z-index: 9999;
    background: #ffffff;
    border: 1px solid #e6dee5;
    box-shadow: 0px 2px 20px rgba(95, 91, 96, 0.25);
    padding-top: 22px;
    padding-bottom: 34px;
    padding-left: 0px;
    padding-right: 20px;
}

.search__input {
    // background: #F9FAFB;
    border: 1px solid #e6dee5;
    border-radius: 5px;
    height: 34px;
    outline: none;
    background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M13.9579 12.9937L9.41321 8.44896C10.1185 7.53722 10.5 6.42247 10.5 5.24998C10.5 3.84648 9.95221 2.53049 8.96171 1.53824C7.97121 0.545997 6.65172 0 5.24998 0C3.84823 0 2.52874 0.547748 1.53824 1.53824C0.545997 2.52874 0 3.84648 0 5.24998C0 6.65172 0.547748 7.97121 1.53824 8.96171C2.52874 9.95396 3.84648 10.5 5.24998 10.5C6.42247 10.5 7.53547 10.1185 8.44721 9.41496L12.9919 13.9579C13.0053 13.9713 13.0211 13.9818 13.0385 13.9891C13.0559 13.9963 13.0746 14 13.0934 14C13.1123 14 13.131 13.9963 13.1484 13.9891C13.1658 13.9818 13.1816 13.9713 13.1949 13.9579L13.9579 13.1967C13.9713 13.1834 13.9818 13.1675 13.9891 13.1501C13.9963 13.1327 14 13.114 14 13.0952C14 13.0763 13.9963 13.0577 13.9891 13.0403C13.9818 13.0228 13.9713 13.007 13.9579 12.9937ZM8.02197 8.02197C7.27997 8.76221 6.29647 9.16996 5.24998 9.16996C4.20348 9.16996 3.21999 8.76221 2.47799 8.02197C1.73774 7.27997 1.32999 6.29647 1.32999 5.24998C1.32999 4.20348 1.73774 3.21824 2.47799 2.47799C3.21999 1.73774 4.20348 1.32999 5.24998 1.32999C6.29647 1.32999 7.28172 1.73599 8.02197 2.47799C8.76221 3.21999 9.16996 4.20348 9.16996 5.24998C9.16996 6.29647 8.76221 7.28172 8.02197 8.02197Z' fill='%23ADB5BD'/%3E%3C/svg%3E%0A");
    background-repeat: no-repeat no-repeat;
    background-position: 10px center;

    font-family: "Roboto";
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 27px;
    color: #adb5bd;

    &:focus {
        outline: none;
    }
}

.filter__apply--button {
    background: linear-gradient(262.9deg, #c52e62 -44.07%, #8b387f 155.41%);
    box-shadow: 1px 3px 15px rgba(0, 0, 0, 0.25);
    border-radius: 10px;
    font-size: 16px;
    line-height: 19px;
}
.filter__reset--button {
    background: #ffffff;
    border: 1.5px solid #8b387f;
    border-radius: 10px;
    font-family: "Inter";
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 19px;

    color: #636363;
}
.middle {
        margin: 10px 5px 0 5px;
        border: 1px solid #8f8f8f;
        width: 13px;
        height: 0px;
    }
</style>

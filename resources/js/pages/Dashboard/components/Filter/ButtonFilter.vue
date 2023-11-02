<template>
    <div
        class="filter__button relative"
        :class="{ 'filter__button--open': openFilterMenu }"
    >
        <button
            class="filter__button--close cursor-pointer flex justify-center items-center w-full font-inter text-[18px] text-center rounded-[10px] px-[10px] py-[5px] font-normal"
            :class="{
                'text-[#FFFFFF]': openFilterMenu,
                'text-[#636363]': !openFilterMenu,
            }"
            @click="
                () => {
                    openFilterMenu = !openFilterMenu;
                    expanded = !expanded;
                }
            "
        >
            {{ $t($attrs.label ?? "") }}
        </button>
        <div
            class="wrapper relative"
            v-if="openFilterMenu"
            v-click-away="
                () => {
                    expanded = false;
                    openFilterMenu = false;
                }
            "
        >
            <div
                class="options absolute z-10 bg-white flex flex-col w-[392px] top-[100%] rounded-b-lg border-[1px] border-x-[1px] border-input max-h-[252px] overflow-y-auto"
                :class="$attrs.optionsClass"
                v-if="expanded"
            >
                <div v-if="searchable" class="pl-4 pr-4 mt-4">
                    <input
                        class="search__input w-full py-2.5 px-5 pl-[30px]"
                        type="text"
                        v-model="search"
                        minlength="1"
                        :placeholder="$t($attrs.placeholder)"
                    />
                </div>
                <div class="">
                    <div class="flex pl-4 pt-4" v-if="allselectable">
                        <div
                            class="select-all flex gap-[10px] items-center cursor-pointer pr-4"
                            @click="
                                () =>
                                    options.length !== $attrs.modelValue.length
                                        ? selectAll()
                                        : null
                            "
                        >
                            <Checkbox
                                :checked="
                                    options.length ==
                                        $attrs.modelValue.length &&
                                    $attrs.modelValue.length
                                "
                            ></Checkbox>
                            <p
                                class="text-[#636363] text-16 font-inter font-normal"
                            >
                                {{ $t("Select all") }}
                            </p>
                        </div>

                        <div
                            class="select-none flex gap-[10px] items-center cursor-pointer"
                            @click="
                                () =>
                                    $attrs.modelValue.length
                                        ? deselectAll()
                                        : null
                            "
                        >
                            <Checkbox
                                :checked="$attrs.modelValue.length == 0"
                            />
                            <p class="text-[#636363] text-16">
                                {{ $t("Select none") }}
                            </p>
                        </div>
                    </div>
                    <div class="pl-8 pt-6 pb-4">
                        <template
                            v-for="(
                                {
                                    [labelKey]: label,
                                    [valueKey]: value,
                                    item: item,
                                },
                                index
                            ) in options"
                            :key="index"
                        >
                            <div
                                class="option flex gap-[10px] items-center cursor-pointer w-[calc(100% - 14px)] break-all pb-4"
                                :class="$attrs.optionClass"
                                @click="toggleSelectedItems(value)"
                            >
                                <Checkbox
                                    :checked="$attrs.modelValue.includes(value)"
                                />
                                <span
                                    class="text-[#636363] text-[16px] leading-4"
                                    :class="[
                                        statusColor
                                            ? item.status == 'Active' ||
                                              item.status == 'active'
                                                ? activeClass
                                                : inactiveClass
                                            : '',
                                        commonText,
                                    ]"
                                    >{{ $t(label ?? "") }}</span
                                >
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "@vue/reactivity";
import Checkbox from "@/components/utils/Checkbox.vue";
import { useAttrs } from "vue";
import { debounce } from "lodash";
import { useRoute, useRouter } from "vue-router";

const emit = defineEmits([
    "update:modelValue",
    "opened",
    "onUpdate",
    "scrolled",
]);

let openFilterMenu = ref(false);
const activeClass = ref("active_text");
const inactiveClass = ref("inactive_text");
const commonText = ref("text_color");
const attrs = useAttrs();
const expanded = ref(false);

const props = defineProps({
    searchable: {
        type: Boolean,
        default: false,
    },
    statusColor: {
        type: Boolean,
        default: false,
    },
    allselectable: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Array,
        requried: true,
    },
    valueKey: {
        type: String,
        default: "value",
    },
    labelKey: {
        type: String,
        default: "label",
    },
    error: {
        type: [Boolean, String],
        requried: false,
    },
    searchables: {
        type: Array,
        required: false,
        default: [],
    },
    topLabel: {
        type: Boolean,
        default: false,
    },
    hierarchyCustom: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    // columnName: {
    //     type: String,
    //     required: true,
    // },
});

const toggleSelectedItems = (value) => {
    const selected_items = attrs.modelValue.includes(value)
        ? attrs.modelValue.filter((item) => item !== value)
        : [...attrs.modelValue, value];
    emit("update:modelValue", selected_items);
    //console.log(value);
};

const selectAll = () => {
    const selected_items = props.options.map(({ value }) => value);
    emit("update:modelValue", selected_items);
};

const deselectAll = () => {
    emit("update:modelValue", []);
};

let search = ref("");
const scroll = ref(null);

const options = computed(() => {
    if (search.value == "") {
        return props.options;
    }

    return props.options.filter((option) => {
        return props.searchables.some((searchKey) =>
            option[searchKey]
                .toLowerCase()
                .startsWith(search.value.toLowerCase())
        );
    });
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
.active_text {
    color: #439f6e;
}
.inactive_text {
    color: #dd2e44;
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
    right: 0;
    top: 48px;
    width: 327px;

    background: #ffffff;
    border: 1px solid #e6dee5;
    box-shadow: 0px 2px 20px rgba(95, 91, 96, 0.25);
    border-radius: 10px 0px 10px 10px;

    padding-top: 22px;
    padding-bottom: 34px;
    padding-left: 27px;
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
</style>

<template>
    <div class="single-select wrapper flex flex-col" :class="$attrs.class">
        <h2
            class="text-input block mb-3 text-formLabel"
            :class="$attrs.labelClass"
            v-if="$attrs.label"
        >
            <template v-if="typeof $attrs.label == 'object'">
                {{ $t($attrs.label.text, $attrs.label.replace) }}
            </template>
            <template v-else>
                {{ $t($attrs.label ?? "") }}{{ $attrs.asterisk ? "*" : "" }}
            </template>
        </h2>

        <div
            class="wrapper relative"
            v-click-away="
                () => {
                    expanded = false;
                    searchable = false;
                }
            "
        >
            <div
                class="toggler flex items-center justify-between bg-white h-10 px-3 border border-input gap-2 cursor-pointer"
                :class="[
                    expanded
                        ? 'rounded-bl-[0px] rounded-br-[0px] rounded-tr-lg rounded-tl-lg'
                        : 'rounded-lg',
                    error ? 'border-error' : '',
                    $attrs.togglerClass,
                ]"
                @click="toggle"
            >
                <slot name="placeholder">
                    <template v-if="searchable">
                        <input
                            class="outline-none w-full h-full"
                            ref="search_input"
                            type="text"
                            v-model="search"
                            @click.stop=""
                        />
                    </template>

                    <template v-else-if="selected_item_label === null">
                        <div class="text-[16px] leading-8 text-bodyText/60">
                            {{ $t($attrs.placeholder ?? "") }}
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="text-ellipsis text-[16px] overflow-hidden whitespace-nowrap text-[#13A3E5]"
                        >
                            <a
                                class="text-[#13A3E5] text-16"
                                @click.stop=""
                                :href="
                                    $router.resolve({
                                        name: 'customer-company-show',
                                        params: { id: selected_item_id },
                                    }).href
                                "
                                target="_blank"
                            >
                                {{ $t(selected_item_label.toString()) }}
                            </a>
                        </div>
                    </template>
                </slot>
                <div class="toggle-icon">
                    <ToggleUpIcon v-if="expanded" />
                    <ToggleDownIcon v-else />
                </div>
            </div>

            <div
                class="options absolute z-10 bg-white flex flex-col w-full top-[100%] rounded-b-lg border-b-[1px] border-x-[1px] border-input max-h-[150px] overflow-y-auto"
                :class="$attrs.optionsClass"
                v-if="expanded && options.length"
            >
                <template
                    v-if="options.length"
                    v-for="{
                        [labelKey]: label,
                        [valueKey]: value,
                        ...additionals
                    } in options"
                >
                    <slot
                        name="option"
                        :option="{
                            label,
                            value,
                            toggle,
                            additionals,
                            selected: value == $attrs.modelValue,
                        }"
                        :toggle="toggleSelectedItem"
                    >
                        <div
                            class="option cursor-pointer px-3 py-[5px] hover:bg-heading hover:text-white"
                            :class="[
                                $attrs.optionClass,
                                value === $attrs.modelValue
                                    ? 'bg-heading text-white'
                                    : 'text-[#636363]',
                            ]"
                            @click="toggleSelectedItem(value, additionals)"
                        >
                            <span class="text-[16px] leading-4">{{
                                $t(label.toString())
                            }}</span>
                        </div>
                    </slot>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import ToggleDownIcon from "@/components/icons/ToggleDown.vue";
import ToggleUpIcon from "@/components/icons/ToggleUp.vue";
import { computed, ref } from "@vue/reactivity";
import { useAttrs } from "vue";
import { useRouter } from "vue-router";

const emit = defineEmits(["update:modelValue", "countryIso"]);
const expanded = ref(false);
const searchable = ref(false);
const search = ref("");
const search_input = ref(null);
const router = useRouter();

const props = defineProps({
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
    searchables: {
        type: Array,
        required: false,
        default: [],
    },
    error: {
        type: [Boolean, String],
        reuried: false,
    },
});

const attrs = useAttrs();

const selected_item_label = computed(() => {
    const selected_options_label = props.options.find((item) => {
        if (
            typeof attrs.modelValue == "string" &&
            typeof item[props.valueKey] == "string"
        ) {
            return (
                item[props.valueKey].toLowerCase() ===
                attrs.modelValue.toLowerCase()
            );
        }
        return item[props.valueKey] === attrs.modelValue;
    });
    return selected_options_label
        ? selected_options_label[props.labelKey]
        : null;
});
const selected_item_id = computed(() => {
    return attrs.modelValue ?? null;
});

const options = computed(() => {
    if (search.value == "") {
        return props.options;
    }
    return props.options.filter((option) => {
        if (props.searchables.length) {
            return props.searchables.some((searchKey) =>
                option[searchKey]
                    .toLowerCase()
                    .startsWith(search.value.toLowerCase())
            );
        } else {
            return option[props.valueKey]
                .toLowerCase()
                .startsWith(search.value.toLowerCase());
        }
    });
});

const toggle = () => {
    expanded.value = !expanded.value;
    if (attrs.searchable) {
        searchable.value = expanded.value;
        if (searchable.value) {
            setTimeout(() => search_input.value.focus(), 0);
        }
    }
};

const toggleSelectedItem = (value, additionals) => {
    toggle();
    emit("countryIso", additionals.country_iso_code);
    emit("update:modelValue", value);
};
</script>

<script>
export default {
    inheritAttrs: false,
};
</script>

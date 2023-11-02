<template>
  <div class="multi-select wrapper flex flex-col" :class="$attrs.class">
    <h2
      class="text-input block mb-3 text-formLabel"
      :class="$attrs.labelClass"
      v-if="$attrs.label"
    >
      <template v-if="typeof $attrs.label == 'object'">
        {{ $t($attrs.label.text, $attrs.label.replace) }}
      </template>
      <template v-else>
        {{ $t($attrs.label ?? "") }}
      </template>
      {{ $attrs.asterisk ? "*" : "" }}
      {{ $attrs.optional ? ` (${$t("optional")})` : "" }}
    </h2>

    <div class="wrapper relative" v-click-away="() => (expanded = false)">
      <div
        class="toggler flex items-center justify-start bg-white h-10 px-3 rounded-lg border border-input gap-2 cursor-pointer"
        :class="[
          expanded ? 'rounded-b-[0px]' : '',
          error ? 'border-error' : '',
          $attrs.togglerClass,
        ]"
        @click="toggle"
      >
        <slot name="placeholder">
          <template v-if="!selected_items_labels.length">
            <div
              v-if="typeof $attrs.placeholder == 'object'"
              class="placeholder"
            >
              {{ $t($attrs.placeholder.text, $attrs.placeholder.replace) }}
            </div>
            <div v-else class="placeholder" style="color: #4b4b4b99;">
              {{ $t($attrs.placeholder ?? "") }}
            </div>
          </template>
          <template v-else>
            <div
              class="text-ellipsis overflow-hidden whitespace-nowrap placeholder"
            >
              {{ selected_items_labels.slice(0, 3).join(", ") }}
            </div>
            <div class="wrapper group relative" v-if="selected_items_labels.length > 3">
              <Pill

                :length="selected_items_labels.length - 3"
              />
              <div
                class="tooltip group-hover:visible invisible absolute top-[120%] right-0 z-20 w-max bg-white flex flex-col border border-input px-[10px] py-[9px] gap-[10px] overflow-y-auto max-h-[200px] rounded-[5px] shadow-[1px_1px_7px_rgba(114,_114,_114,_0.25)] transition-all duration-100 ease-linear"
              >
                <div
                  class="label flex gap-[10px] items-center tooltip-label"
                  v-for="(label, index) in selected_items_labels.slice(
                    3,
                    selected_items_labels.length
                  )"
                  :key="index"
                >
                  {{ label }}
                </div>
              </div>
            </div>
          </template>
        </slot>
        <div class="toggle-icon ml-auto">
          <ToggleUpIcon v-if="expanded" />
          <ToggleDownIcon v-else />
        </div>
      </div>

      <div
        class="options absolute z-10 bg-white flex flex-col w-full top-[99%] rounded-b-lg border border-input border-t-0 p-[14px] gap-[10px] max-h-[150px] overflow-y-auto"
        :class="$attrs.optionsClass"
        v-if="expanded"
        @scroll="scrollCheck"
        ref="scroll"
      >
        <template
          v-for="({ [labelKey]: label, [valueKey]: value }, index) in options"
          :key="index"
        >
          <slot name="option" :option="{ label, value }" :toggle="toggleSelectedItems">
            <div
              class="option flex gap-[10px] items-center cursor-pointer"
              :class="$attrs.optionClass"
              @click="toggleSelectedItems(value)"
            >
              <Checkbox :checked="$attrs.modelValue.includes(value)" />
              <span
                class="option"
                :class="$attrs.optionTextClass"
                >{{ label ?? "" }}</span
              >
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
import Checkbox from "@/components/utils/Checkbox.vue";

import { computed, ref } from "@vue/reactivity";
import { useAttrs } from "vue";
import Pill from "@/components/utils/Pill.vue";
import {debounce} from 'lodash'
import {trans} from 'laravel-vue-i18n'

const attrs = useAttrs();
const emit = defineEmits(["update:modelValue", "opened", 'scrolled' , "onUpdate"]);
const expanded = ref(false);


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
  error: {
    type: [Boolean, String],
    requried: false,
  },
});

const selected_items_labels = computed(() => {
  const selected_options_labels = props.options
    .filter(({ [props.valueKey]: value, [props.labelKey]: label }) => {
      return attrs.modelValue.includes(value);
    })
    .map((option) => trans(option[props.labelKey]));

  return selected_options_labels;
});

const toggle = () => {
  expanded.value = !expanded.value;
  if (expanded.value) emit("opened");
};

const toggleSelectedItems = (value) => {
  const selected_items = attrs.modelValue.includes(value)
    ? attrs.modelValue.filter((item) => item !== value)
    : [...attrs.modelValue, value];
  emit("update:modelValue", selected_items);
  emit("onUpdate", value);
};

const scroll = ref(null)

const scrollCheck  = debounce(() => {
    const el = scroll.value
    const { scrollTop, scrollHeight, clientHeight } = el

    // console.log(scrollTop, scrollHeight, clientHeight)
    if (scrollTop + clientHeight >= scrollHeight - 100) {
        emit("scrolled")
    }
}, 200)


</script>

<script>
export default {
  inheritAttrs: false,
};
</script>

<style lang="scss" scoped>
    .text-formLabel {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.02em;

        color: #555555;
    }

    .placeholder {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 16px;
        color: #636363;
        // color: #4b4b4b99;
    }

    .option {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 16px;
        color: #636363;
        width: calc(100% - 14px);
        word-break: break-all;
    }

    .tooltip-label {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 16px;
        color: #636363;
    }
</style>

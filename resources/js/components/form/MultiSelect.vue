<template>
  <div class="multi-select wrapper flex flex-col" :class="$attrs.class">
    <h2 class="text-input block mb-3 text-formLabel whitespace-nowrap" :class="$attrs.labelClass" v-if="$attrs.label">
      <template v-if="typeof $attrs.label == 'object'"> {{ $t($attrs.label.text, $attrs.label.replace) }}{{ $attrs.asterisk ? '*' : '' }}{{ $attrs.optional ? ` (${$t('optional')})` : '' }} </template>
      <template v-else> {{ $t($attrs.label ?? '') }}{{ $attrs.asterisk ? '*' : '' }}{{ $attrs.optional ? ` (${$t('optional')})` : '' }} </template>
    </h2>

    <div class="wrapper relative" v-click-away="() => (expanded = false)">
      <div
        class="toggler flex items-center justify-start bg-white h-10 px-3 rounded-lg border border-input gap-2 cursor-pointer"
        :class="[expanded ? 'rounded-b-[0px]' : '', error ? '!border-error' : '', $attrs.togglerClass]"
        @click="toggle"
      >
        <slot name="placeholder">
          <template v-if="searchables.length && expanded && !$attrs.hide_search_input">
            <input class="search__input outline-none w-full h-full pl-[30px] text-[#636363] text-16" ref="search_input" type="text" v-model="search" @click.stop="" />
          </template>

          <template v-else-if="!selected_items_labels.length">
            <div v-if="typeof $attrs.placeholder == 'object'" class="text-[16px] leading-8 text-bodyText/60">
              {{ $t($attrs.placeholder.text, $attrs.placeholder.replace) }}
            </div>
            <div v-else class="text-[16px] leading-8 text-bodyText/60">
              <slot name="placeholder-text" :placeholder="$attrs.placeholder">
                {{ $t($attrs.placeholder ?? '') }}
              </slot>
            </div>
          </template>

          <template v-else>
            <slot name="selected-items-label" :selected_items_labels="selected_items_labels">
              <div class="text-ellipsis text-[16px] overflow-hidden whitespace-nowrap text-[#636363]">
                {{ selected_items_labels.slice(0, 3).join(', ') }}
              </div>
            </slot>
            <div class="wrapper group relative" v-if="selected_items_labels.length > 3">
              <Pill :length="selected_items_labels.length - 3" />
              <div
                class="tooltip group-hover:visible invisible absolute top-[120%] z-20 w-max bg-white flex flex-col border border-input px-[10px] py-[9px] gap-[10px] overflow-y-auto max-h-[200px] rounded-[5px] shadow-[1px_1px_7px_rgba(114,_114,_114,_0.25)] transition-all duration-100 ease-linear"
              >
                <div class="label flex gap-[10px] items-center text-[#636363] text-4 leading-4" v-for="(label, index) in selected_items_labels.slice(3, selected_items_labels.length)" :key="index">
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
        class="options absolute z-10 bg-white flex flex-col w-full top-[100%] rounded-b-lg border border-input border-t-0 p-[14px] gap-[10px] max-h-[150px] overflow-y-auto"
        :class="$attrs.optionsClass"
        v-if="expanded"
      >
        <slot name="options-top" :search="search" :setSearch="(value) => (search = value)"></slot>

        <template v-for="({ [labelKey]: label, [valueKey]: value }, index) in options" :key="index">
          <slot name="option" :option="{ label, value }" :toggle="toggleSelectedItems">
            <div class="option flex gap-[10px] items-center cursor-pointer w-[calc(100% - 14px)] break-all" :class="$attrs.optionClass" @click="toggleSelectedItems(value)">
              <Checkbox :checked="$attrs.modelValue.includes(value)" />
              <span class="text-[#636363] text-[16px] leading-4" :class="$attrs.optionTextClass">{{ $t(label ?? '') }}</span>
            </div>
          </slot>
        </template>

        <slot name="options-bottom" :search="search" :setSearch="(value) => (search = value)"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import ToggleDownIcon from '@/components/icons/ToggleDown.vue'
import ToggleUpIcon from '@/components/icons/ToggleUp.vue'
import Checkbox from '@/components/utils/Checkbox.vue'

import { computed, ref } from '@vue/reactivity'
import { useAttrs } from 'vue'
import Pill from '@/components/utils/Pill.vue'

const attrs = useAttrs()
const emit = defineEmits(['update:modelValue', 'opened'])
const expanded = ref(false)

const props = defineProps({
  options: {
    type: Array,
    requried: true,
  },
  valueKey: {
    type: String,
    default: 'value',
  },
  labelKey: {
    type: String,
    default: 'label',
  },
  error: {
    type: [Boolean, String],
    requried: false,
  },
  searchables: {
    type: Array,
    default: () => [],
  },
})
const search = ref('')
const search_input = ref(null)

const options = computed(() => {
  if (search.value == '') {
    return props.options
  }

  return props.options.filter((option) => {
    return props.searchables.some((searchKey) => option[searchKey].toLowerCase().startsWith(search.value.toLowerCase()))
  })
})

const selected_items_labels = computed(() => {
  const selected_options_labels = props.options
    .filter(({ [props.valueKey]: value, [props.labelKey]: label }) => {
      return attrs.modelValue.includes(value)
    })
    .map((option) => option[props.labelKey])

  return selected_options_labels
})

const toggle = () => {
  expanded.value = !expanded.value
  if (expanded.value) {
    emit('opened')
    setTimeout(() => (props.searchables.length ? search_input.value?.focus() : null), 0)
  }
}

const toggleSelectedItems = (value) => {
  const selected_items = attrs.modelValue.includes(value) ? attrs.modelValue.filter((item) => item !== value) : [...attrs.modelValue, value]
  emit('update:modelValue', selected_items)
}
</script>

<script>
export default {
  inheritAttrs: false,
}
</script>

<style>
.search__input {
  background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 12 12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11.9639 11.1375L8.06847 7.24197C8.67296 6.46047 8.99996 5.50498 8.99996 4.49998C8.99996 3.29699 8.53046 2.16899 7.68147 1.31849C6.83247 0.467998 5.70147 0 4.49998 0C3.29849 0 2.16749 0.469498 1.31849 1.31849C0.467998 2.16749 0 3.29699 0 4.49998C0 5.70147 0.469498 6.83247 1.31849 7.68147C2.16749 8.53196 3.29699 8.99996 4.49998 8.99996C5.50498 8.99996 6.45897 8.67296 7.24047 8.06996L11.136 11.9639C11.1474 11.9754 11.1609 11.9844 11.1759 11.9906C11.1908 11.9968 11.2068 12 11.223 12C11.2391 12 11.2551 11.9968 11.27 11.9906C11.285 11.9844 11.2985 11.9754 11.3099 11.9639L11.9639 11.3114C11.9754 11.3 11.9844 11.2865 11.9906 11.2715C11.9968 11.2566 12 11.2406 12 11.2245C12 11.2083 11.9968 11.1923 11.9906 11.1774C11.9844 11.1624 11.9754 11.1489 11.9639 11.1375ZM6.87597 6.87597C6.23997 7.51047 5.39698 7.85997 4.49998 7.85997C3.60298 7.85997 2.75999 7.51047 2.12399 6.87597C1.48949 6.23997 1.13999 5.39698 1.13999 4.49998C1.13999 3.60298 1.48949 2.75849 2.12399 2.12399C2.75999 1.48949 3.60298 1.13999 4.49998 1.13999C5.39698 1.13999 6.24147 1.48799 6.87597 2.12399C7.51047 2.75999 7.85997 3.60298 7.85997 4.49998C7.85997 5.39698 7.51047 6.24147 6.87597 6.87597Z' fill='%23ADB5BD'/%3E%3C/svg%3E%0A");
  background-repeat: no-repeat no-repeat;
  background-position: 0px center;
  padding-right: 20px;
}
</style>

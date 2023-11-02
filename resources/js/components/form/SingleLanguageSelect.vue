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
          error ? 'border-error' : '',
          disabled ? 'bg-[#FAFAFA]': '',
          $attrs.togglerClass,
        ]"
        @click="toggle"
      >
        <slot name="placeholder">
          <template v-if="searchable">
            <input
              class="outline-none w-full h-full"
              type="text"
              v-model="search"
              :placeholder="$t(attrs.placeholder ?? 'Search')"
              @click.stop="toggle"
            />
          </template>
          <template v-else-if="selected_item_label === null">
            <div class="text-[16px] leading-8 text-bodyText/60">
              {{ $t($attrs.placeholder ?? "") }}
            </div>
          </template>
          <template v-else>
            <div
              class="text-ellipsis text-[16px] overflow-hidden whitespace-nowrap text-[#636363]"
              :class="[disabled? 'text-[#585858]': '']"
            >
              {{ $t(selected_item_label.toString()) }}
            </div>
          </template>
        </slot>
        <div class="toggle-icon" v-if="!disabled">
          <ToggleUpIcon v-if="expanded" />
          <ToggleDownIcon v-else />
        </div>
      </div>

      <div
        class="options absolute z-10 bg-white flex flex-col w-full top-[100%] rounded-b-lg border-b-[1px] border-x-[1px] border-input max-h-[150px] overflow-y-auto"
        :class="$attrs.optionsClass"
        v-if="expanded && options.length"
      >
        <slot
            name="options-top"
            :search="search"
            :setSearch="(value) => (search = value)"
          ></slot>
        <template
          v-for="({ ['name']: label, ['code']: value }, index) in options"
          :key="index"
        >
          <slot
            name="option"
            :option="{ label, value }"
            :toggle="toggleSelectedItem"
          >
            <div
              class="option cursor-pointer px-3 py-[5px] hover:bg-heading hover:text-white"
              :key="value"
              :class="[
                $attrs.optionClass,
                value === $attrs.modelValue ? 'bg-heading text-white' : 'text-[#636363]',
              ]"
              @click="toggleSelectedItem(value)"
            >
              <span class="text-[16px] leading-4">{{ $t(label ?? "") }}</span>
            </div>
          </slot>
        </template>
        <slot
            name="options-bottom"
            :search="search"
            :setSearch="(value) => (search = value)"
        ></slot>
      </div>
    </div>
  </div>
  </template>

  <script setup>
  import ToggleDownIcon from "@/components/icons/ToggleDown.vue";
  import ToggleUpIcon from "@/components/icons/ToggleUp.vue";
  import { computed, ref } from "@vue/reactivity";
  import { useAttrs } from "vue";
  import languages from '@/language.json'
  import languagesPlugin from "@cospired/i18n-iso-languages"
  import { useUserStore } from '@/store/user'

  const {user}= useUserStore();
  const translatedLanguages = languages.map((language) => ({
        code: language.code,
        name: languagesPlugin.getName(language.code, user.language.code),
    }))

  const emit = defineEmits(["update:modelValue", "onUpdate"]);
  const expanded = ref(false);
  const search_input = ref(null)

  const props = defineProps({
    searchable: {
      type: Boolean,
      default: false,
    },
    error: {
      type: [Boolean, String],
      reuried: false,
    },
    disabled: {
        type: Boolean,
        default: false
    }
  });

  const attrs = useAttrs();

  const selected_item_label = computed(() => {
  const selected_options_label = translatedLanguages.find((item) => {
    if (typeof attrs.modelValue == "string" && typeof item['code'] == "string") {
      return item['code'].toLowerCase() === attrs.modelValue.toLowerCase();
    }
    return item['code'] === attrs.modelValue;
  });
  return selected_options_label ? selected_options_label['name'] : null;
});

const search = attrs.modelValue ? ref(languagesPlugin.getName(attrs.modelValue, user.language.code)) : ref('');
const all_options_show = attrs.modelValue ? ref(true) : ref(false);

  const options = computed(() => {
    all_options_show.value = search.value != '' && all_options_show.value == true ? true : false;
    if (all_options_show.value == true) {
      return translatedLanguages;
    }
        return translatedLanguages.filter((option) => {
            return option['name'].toLowerCase().startsWith(search.value.toLowerCase())
        });
  });

  const toggle = () => {
    if(props.disabled) return ;
    expanded.value = !expanded.value;
  };

  const toggleSelectedItem = (value) => {
    toggle();
    search.value=languagesPlugin.getName(value, user.language.code)
    all_options_show.value = true
    emit("update:modelValue", value);
    emit('onUpdate')
  };
  </script>

  <script>
  export default {
    inheritAttrs: false,
  };
  </script>
   <style>
   .search__input {
     background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 12 12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11.9639 11.1375L8.06847 7.24197C8.67296 6.46047 8.99996 5.50498 8.99996 4.49998C8.99996 3.29699 8.53046 2.16899 7.68147 1.31849C6.83247 0.467998 5.70147 0 4.49998 0C3.29849 0 2.16749 0.469498 1.31849 1.31849C0.467998 2.16749 0 3.29699 0 4.49998C0 5.70147 0.469498 6.83247 1.31849 7.68147C2.16749 8.53196 3.29699 8.99996 4.49998 8.99996C5.50498 8.99996 6.45897 8.67296 7.24047 8.06996L11.136 11.9639C11.1474 11.9754 11.1609 11.9844 11.1759 11.9906C11.1908 11.9968 11.2068 12 11.223 12C11.2391 12 11.2551 11.9968 11.27 11.9906C11.285 11.9844 11.2985 11.9754 11.3099 11.9639L11.9639 11.3114C11.9754 11.3 11.9844 11.2865 11.9906 11.2715C11.9968 11.2566 12 11.2406 12 11.2245C12 11.2083 11.9968 11.1923 11.9906 11.1774C11.9844 11.1624 11.9754 11.1489 11.9639 11.1375ZM6.87597 6.87597C6.23997 7.51047 5.39698 7.85997 4.49998 7.85997C3.60298 7.85997 2.75999 7.51047 2.12399 6.87597C1.48949 6.23997 1.13999 5.39698 1.13999 4.49998C1.13999 3.60298 1.48949 2.75849 2.12399 2.12399C2.75999 1.48949 3.60298 1.13999 4.49998 1.13999C5.39698 1.13999 6.24147 1.48799 6.87597 2.12399C7.51047 2.75999 7.85997 3.60298 7.85997 4.49998C7.85997 5.39698 7.51047 6.24147 6.87597 6.87597Z' fill='%23ADB5BD'/%3E%3C/svg%3E%0A");
     background-repeat: no-repeat no-repeat;
     background-position: 0px center;
     padding-right: 20px;
   }
   </style>

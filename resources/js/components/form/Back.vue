<template>
  <div class="back flex gap-[11px] items-center cursor-pointer" @click="redirect">
    <LeftArrowIcon />
    <span class="text-[16px] leading-[32px] text-[#636363] font-[500]">{{
      $t("Back")
    }}</span>
  </div>
</template>

<script setup>
import LeftArrowIcon from "@/components/icons/LeftArrow.vue";
import { useRouter } from "vue-router";
import { inject } from "@vue/runtime-core";

const router = useRouter();
const props = defineProps({
  title: {
    type: String,
    default: "Discard Changes?",
  },
  description: {
    type: String,
    default:
      "If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?",
  },
  show_modal: {
    type: Boolean,
    default: false,
  },
  target: {
    type: String,
    requred: false,
  },
});

const $vfm = inject("$vfm");

const redirect = () => {
  if (props.show_modal) {
    $vfm.show("redirect-modal", {
      title: props.title,
      description: props.description,
      target: props.target,
    });
  } else {
    props.target ? router.push(target) : router.back();
  }
};
</script>

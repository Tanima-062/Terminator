<template>
  <SingleSelect class="max-w-[290px]" optionsClass="px-[17px] py-[14px]">
    <template
      #option="{
        option: { label, value, toggle, additionals, selected },
        toggle: setSelectedItem,
      }"
    >
      <div
        class="option flex gap-[10px] py-[6px] items-center cursor-pointer"
        @click="setSelectedItem(value)"
      >
        <RadioInputIcon :active="selected" />
        <p class="p-0 text-[#636363] text-[16px] leading-[19px]">{{ label }}</p>
      </div>
    </template>
  </SingleSelect>
</template>

<script setup>
import RadioInputIcon from "@/components/icons/RadioInput.vue";
import SingleSelect from "@/components/form/SingleSelect.vue";
</script>

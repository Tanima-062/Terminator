<template>
  <button
    class="w-full block hover:pointer text-[16px] leading-[19px] rounded-sm shadow-[1px_3px_15px_rgba(0,_0,_0,_0.25)] bg-gradient-to-r from-btnGradient2 to-btnGradient1 font-inter text-white text-center px-[30px] py-[15px] font-medium"
    type="button"
    :class="$attrs.class"
  >
    <slot />
  </button>
</template>

<script setup>
</script>

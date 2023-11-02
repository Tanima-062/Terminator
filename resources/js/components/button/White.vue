<template>
  <button
    :class="$attrs.class" class="w-full block p-[1px] bg-gradient-to-r from-btnGradient2 to-btnGradient1 cursor-pointer text-[#5F4C5C] rounded-[10px]"
  >
    <div class="bg-white rounded-[8px] h-full w-full font-medium flex items-center justify-center">
      <slot />
    </div>
  </button>
</template>

<script setup></script>

<template>
  <button
    class="w-full block  text-[16px] leading-[19px] rounded-sm  font-inter text-white text-center px-[30px] py-[15px] font-medium "
    type="button"
    :class="[{'bg-gradient-to-r from-btnGradient2  to-btnGradient1 hover:pointer': !disabled, 'bg-[#BBBBBB] cursor-default pointer-events-none': disabled}, $attrs.class]"
  >
    <slot />
  </button>
</template>

<script>
export default {
    props: {
        disabled: {
            type: Boolean,
            default: false
        }
    }
}
</script>

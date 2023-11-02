<template>
    <div :class="modelValue ? 'bg-[#118D2C]' : 'bg-[#636363]'" @click="toggle"
        class="w-[45px] transition-all duration-500 h-[22px] flex items-center rounded-full p-1 hover:cursor-pointer">
        <div :class="{ 'translate-x-6 -ml-[3px]': modelValue }"
            class="w-[16px] h-[16px] transition-all duration-500 transform bg-white rounded-full shadow-md"></div>
    </div>
</template>

<script setup>
    const props = defineProps({
        modelValue: {
            type: Boolean
        }
    })
    // console.log(props.modelValue)
    const emit = defineEmits(['update:modelValue'])
    function toggle() {
        emit('update:modelValue', !props.modelValue)
    }
</script>

<style scoped>

</style>

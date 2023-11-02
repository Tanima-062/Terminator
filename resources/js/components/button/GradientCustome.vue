<template>
    <button
        class="w-full block hover:pointer text-[22px] leading-[19px] rounded-sm shadow-[1px_3px_15px_rgba(0,_0,_0,_0.25)] bg-gradient-to-tl from-[#B7316A] to-[#7D3272] font-'Poppins text-white text-center px-[30px] py-[15px] font-normal"
        type="button"
        :class="$attrs.class"
    >
        <slot />
    </button>
</template>

<script setup></script>

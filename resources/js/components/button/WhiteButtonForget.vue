<template>
    <!-- <button :class="$attrs.class"
        class="w-full block p-[1px] bg-gradient-to-r from-btnGradient2 to-btnGradient1 cursor-pointer  rounded-[10px]">
        <div class="bg-white rounded-[8px] py-[15px] h-full w-full font-medium flex items-center justify-center text-[#5F4C5C]">
            <slot />
        </div>
    </button> -->
    <button  :class="$attrs.class" class="h-[50px] w-full rounded-[10px] bg-gradient-to-r from-btnGradient2 to-btnGradient1 cursor-pointer p-[1px]">
        <!-- <div class="h-36 w-full rounded-md bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 p-1"> -->
            <div class="flex h-full w-full items-center justify-center bg-white back rounded-[9px] font-medium font-inter text-[16px]  text-[#5F4C5C] p-4">
                <slot />
            </div>
        <!-- </div> -->
    </button>
</template>

<script setup></script>

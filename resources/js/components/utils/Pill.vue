<template>
    <div
        class="pill relative flex items-center justify-center text-white text-[10px] font-poppins leading-[15px] w-6 h-6"
        :style="{
            width: ($attrs.radius ? `${$attrs.radius * 2}px` : ''),
            height: ($attrs.radius ? `${$attrs.radius * 2}px` : ''),
        }"
        v-if="$attrs.length"
    >
        <svg
            class="absolute top-0 left-0 "
            :width="$attrs.radius ? ($attrs.radius * 2) : 24"
            :height="$attrs.radius ? ($attrs.radius * 2) : 24"
            :viewBox="$attrs.radius ? `0 0 ${$attrs.radius * 2} ${$attrs.radius * 2}` : '0 0 24 24'"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <circle
                class="group-hover:fill-[#7E7E7E]"
                :class="$attrs.circleClass"
                :cx="$attrs.radius ?? 12"
                :cy="$attrs.radius ?? 12"
                :r="$attrs.radius ?? 12"
                :fill="$attrs.fill ?? '#AB326F'"
            />
        </svg>

        <span class="z-10">
           +{{ $attrs.length }}
        </span>
    </div>
</template>

<script setup></script>

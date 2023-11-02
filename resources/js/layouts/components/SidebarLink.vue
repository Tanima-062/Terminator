<template>
    <RouterLink
        v-bind="$props"
        custom
        v-slot="{ isActive, href, navigate }"
    >
        <a
            v-bind="$attrs"
            :href="href"
            @click="navigate"
            class="flex items-center hover:cursor-pointer h-[54px] w-full"
            :class="[((isActive || $attrs.activeCondition()) && !$attrs.collapse) ? 'bg-white rounded-[6px] shadow-link' : '', !$attrs.collapse ? 'px-3 py-[7px]' : '']"
        >
            <div class="icon ml-[-18px]">
                <slot
                    name="icon"
                    :active="isActive || $attrs.activeCondition()"
                />
            </div>
            <div
                class="link sm:hidden md:block"
                :class="{ '!hidden': $attrs.collapse }"
            >
                <p class="font-inter text-h4 leading-7 text-[#5F4C5C] font-[500] whitespace-nowrap">
                    <slot
                        name="name"
                        :active="isActive || $attrs.activeCondition()"
                    />
                </p>
            </div>
        </a>
    </RouterLink>
</template>

<script setup></script>

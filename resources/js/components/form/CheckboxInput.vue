<template>
    <div :class="$attrs.class">
        <h2 class="text-input block mb-3 text-formLabel" v-if="label" :class="labelClass">{{ $t(label) }}</h2>
        <div class="flex items-center gap-2.5 max-w-fit cursor-pointer"  @click="$emit('update:modelValue', !modelValue)">
            <template v-if="modelValue">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.5" y="0.5" width="16" height="16" rx="1.5" stroke="#AB326F"/>
                    <rect x="3" y="3" width="11" height="11" rx="1" fill="#AB326F"/>
                </svg>
            </template>
            <template v-else>
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.5" y="0.5" width="16" height="16" rx="1.5" stroke="#A4A4A4"/>
                </svg>
            </template>
            <p class="text-[#636363] text-16 ">
                {{ $t(checkLabel) }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            modelValue: {
                type: Boolean,
                required: true
                // default:  null
            },
            label: {
                type: String,
                default: null
            },
            checkLabel: {
                type: String,
                required: true
            },
            labelClass: {
                type: String,
                default:""
            }
        },
    }
</script>

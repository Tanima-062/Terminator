<template>
    <div class="input-element w-full" :class="$attrs.wrapperClass">
        <label
            :for="$attrs.id"
            class="text-input block mb-3 text-formLabel"
            :class="$attrs.labelClass"
            v-if="label"
        >
            <template v-if="typeof $attrs.label == 'object'">
                {{ $t(label.text, label.replace) }}
            </template>
            <template v-else>
                {{ $t(label) }}{{ $attrs.asterisk ? '*' : '' }}
            </template>
        </label>
        <input
            class="w-full h-10 text-input placeholder-bodyText/60 leading-[32px] border border-input rounded-[8px] bg-white outline-none px-3"
            :class="{ 'border-error': error }"
            v-bind="$attrs"
            :placeholder="typeof $attrs.placeholder == 'object' ? $t($attrs.placeholder.text, $attrs.placeholder.replace) : $t($attrs.placeholder ?? '')"
            :value="$attrs.modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p
            class="error-box flex p-0 items-center gap-2 mt-[4px] text-error text-12"
            v-if="(typeof error == 'string' && error)"
        >
            <ErrorCrossIcon /> {{ $t(error) }}
        </p>
    </div>
</template>

<script setup>
import ErrorCrossIcon from '@/components/icons/ErrorCross.vue'
defineProps({
    label: {
        type: [String, Object],
        required: false,
        default: ''
    },
    error: {
        type: [Boolean, String],
        required: false
    },
})
</script>

<script>
export default {
    inheritAttrs: false
}
</script>

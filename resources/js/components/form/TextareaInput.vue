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
        <textarea
            class="w-full text-input placeholder-bodyText/60 leading-[32px] border border-input rounded-[8px] bg-white outline-none px-3 overflow-hidden min-h-[100px]"
            :class="{ 'border-error': error }"
            v-bind="$attrs"
            :placeholder="typeof $attrs.placeholder == 'object' ? $t($attrs.placeholder.text, $attrs.placeholder.replace) : $t($attrs.placeholder ?? '')"
            :value="$attrs.modelValue"
            @input="updateInput"
            ref="textarea"
        ></textarea>
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
import { ref } from 'vue'
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
const emit = defineEmits(['update:modelValue'])

const textarea = ref(null);
const updateInput = (e)=> {
    textarea.value.style.height = (textarea.value.scrollHeight) + 'px';
    emit('update:modelValue', e.target.value)
}
</script>

<script>
export default {
    inheritAttrs: false
}
</script>

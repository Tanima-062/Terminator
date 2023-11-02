<template>
    <div class="radio-input flex flex-col">
        <h2
            class="text-input text-formLabel mb-[15px]"
            :class="$attrs.labelClass"
            v-if="$attrs.label"
        >
            <template v-if="typeof $attrs.label == 'object'">
                {{ $t($attrs.label.text, $attrs.label.replace) }}
            </template>
            <template v-else>
                {{ $t($attrs.label) }}{{ $attrs.asterisk ? '*' : '' }}
            </template>
        </h2>

        <div
            class="options flex gap-10"
            v-bind="$attrs"
        >
            <div
                class="option flex items-center gap-[10px]"
                :class="$attrs.optionClass"
                v-for="{ value, label } in options"
            >
                <slot
                    name="icon"
                    :option="{ value, label }"
                >
                    <div
                        class="icon"
                        @click="() => { $emit('update:modelValue', value); $emit('updated', value) }"
                    >
                        <template v-if="$attrs.modelValue == value">
                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 15 15"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                    x="0.5"
                                    y="0.5"
                                    width="14"
                                    height="14"
                                    rx="7"
                                    stroke="#AB326F"
                                />
                                <circle
                                    cx="7.5"
                                    cy="7.5"
                                    r="4.5"
                                    fill="#AB326F"
                                />
                            </svg>
                        </template>

                        <template v-else>
                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 15 15"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                    x="0.5"
                                    y="0.5"
                                    width="14"
                                    height="14"
                                    rx="7"
                                    stroke="#676767"
                                />
                            </svg>
                        </template>
                    </div>
                </slot>

                <slot
                    name="label"
                    :option="{ value, label }"
                >
                    <span class="text-4 text-input leading-[19px]">{{ $t(label) }}</span>
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    options: {
        type: Array,
        required: true,
    }
})
</script>

<script>
export default {
    inheritAttrs: false
}
</script>

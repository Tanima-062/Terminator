<template>
    <div class="wrapper">

        <div class="label mb-3 flex gap-[8px]">
             <label
                :for="$attrs.id"
                class="text-input block mb-3 text-formLabel"
                :class="$attrs.labelClass" v-if="label"
                >{{$t(label) }} {{ $attrs.asterisk ? '*' : '' }}
            </label>
            <PasswordTooltip v-if="$attrs.showTooltip" />
        </div>
        <div class="password-input toggleable relative">
            <input
                :id="id"
                class="w-full h-10 text-input placeholder-bodyText/60 leading-[32px] border border-input rounded-[8px] bg-white outline-none px-3"
                :class="{ 'border-error': error }"
                v-bind="$attrs"
                :value="$attrs.modelValue"
                :placeholder="placeholder"
                @input="$emit('update:modelValue', $event.target.value)"
                :type="type"
            />

            <div
                v-show="toggleable"
                class=" absolute top-[50%] right-[3%] translate-y-[-50%] cursor-pointer"
                @click="type == 'password' ? type = 'text' : type = 'password'"
            >
                <eye-close v-show="type != 'password'" />
                <eye-open v-show="type == 'password'" />
            </div>
        </div>
        <p class="error-box flex p-0 items-center gap-2 mt-[4px] font-normal font-inter text-error text-[13px]" v-if="(typeof error == 'string' && error)">
            <ErrorCrossIcon /> {{ $t(error) }}
        </p>
    </div>
</template>

<script setup>
import EyeClose from "../icons/EyeClose.vue";
import EyeOpen from "../icons/EyeOpen.vue";
import PasswordTooltip from "./PasswordTooltip.vue";
import ErrorCrossIcon from '@/components/icons/ErrorCross.vue'

const props = defineProps({
    label: {
        type: String,
        requried: false,
    },

    id: {
        type: String,
        required: false,
    },
    type: {
        type: String,
        required: true,
    },

    placeholder: {
        type: String,
        required: true,
    },

    toggleable: {
        type: Boolean,
        default: true,
    },

    error: {
        type: [String, Boolean],
        required: false,
    },
});

</script>

<script>
export default {
    inheritAttrs: false,
    components: { PasswordTooltip }
}
</script>

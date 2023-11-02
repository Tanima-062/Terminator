<template>
    <div class="datepicker">
        <v-date-picker
            v-model="dateForm"
            is-range
            title-position="left"
            @dayclick="dayclick"
            :attributes="datepickerOptions.attrs"
            :locale="userStore.user.language.code"
            :select-attribute="{
                highlight: {
                    start: {
                        fillMode: 'solid',
                        style: { background: '#9E3576' },
                    },
                    base: {
                        fillMode: 'light',
                        contentStyle: { color: '#ffffff' },
                        style: { background: '#C73B81' },
                    },
                    end: {
                        fillMode: 'solid',
                        style: { background: '#9E3576' },
                    },
                },
            }"
            :drag-attribute="{
                highlight: {
                    start: {
                        fillMode: 'outline',
                        contentStyle: { color: '#000000' },
                        style: { borderColor: '#9E3576' },
                    },
                    base: {
                        fillMode: 'solid',
                        contentStyle: { color: '#FFF' },
                        style: { background: '#C73B81' },
                    },
                    end: {
                        fillMode: 'outline',
                        contentStyle: { color: '#000000' },
                        style: { borderColor: '#9E3576' },
                    },
                },
            }"
            :key="compoenentKey"
            :masks="{ input: 'DD.MM.YYYY', title: 'MMM YYYY' }"
            :model-config="{
                type: 'string',
                mask: 'YYYY-MM-DD', // Uses 'iso' if missing
            }"
        >
            <template v-slot="{ inputValue, inputEvents }">
                <div class="filter-date-range-picker">
                    <input
                        id="date"
                        class="date__input"
                        :value="inputValue.start"
                        v-on="inputEvents.start"
                        :placeholder="$t('dd.mm.yyyy')"
                    />
                    <span class="middle"></span>
                    <input
                        id="date"
                        class="date__input"
                        :value="inputValue.end"
                        v-on="inputEvents.end"
                        :placeholder="$t('dd.mm.yyyy')"
                    />
                </div>
            </template>
        </v-date-picker>
    </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { computed, onMounted, useAttrs, watch } from "@vue/runtime-core";
import { useUserStore } from "@/store/user.js";
import { storeToRefs } from "pinia";

export default {
    // props: {
    //     start_date: null,
    //     end_date: null,
    // },
    setup(props, { emit }) {
        const attrs = useAttrs();
        const compoenentKey = ref(0);
        const { user } = storeToRefs(useUserStore());
        const dateForm = computed({
            get: () => ({
                end: attrs.end_date,
                start: attrs.start_date,
            }),
            set: (value) => {
                if (value !== null) {
                    emit("update:start_date", value.start ?? null);
                    emit("update:end_date", value.end ?? null);
                }
            },
        });

        const datepickerOptions = reactive({
            attrs: [
                {
                    key: "today",
                    highlight: "green",
                    dates: new Date(),
                },
            ],
        });

        const dayclick = (e) => {};

        return {
            dateForm,
            dayclick,
            datepickerOptions,
            compoenentKey,
            user,

            // rangeDays
        };
    },
};
</script>

<style lang="scss" scoped>
.filter-date-range-picker {
    display: flex;
    .middle {
        margin: 15px 5px 0 5px;
        border: 1px solid #8f8f8f;
        width: 23.02px;
        height: 0px;
    }

    .date__input {
        width: 94px;
        border: 1px solid #e6dee5;
        border-radius: 8px;
        height: 34px;
        padding-left: 6px;
        outline: none;
        font-family: "Inter";
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 16px;
        letter-spacing: 0.04em;

        color: #636363;

        &:focus {
            outline: none;
        }
    }
}
</style>

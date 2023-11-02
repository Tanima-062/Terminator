<template>
    <div class="dashboard w-[561px] h-[750px]">
        <h3
            class="text-center text-[#AB326F] font-inter text-[20px] font-semibold"
        >
            {{ $t(title) }}
        </h3>

        <div class="flex justify-center pt-8 pb-14" v-if="total">
            <div class="max-w-3xl">
                <p
                    class="dashboard_pie_top_titles pb-2"
                    v-for="(value, index) in total.label"
                    :key="index"
                >
                    {{ $t(index) }}:
                    <span class="font-inter font-medium"
                        ><span v-if="index == 'total_cost_data' || index == 'total_profit_data'">{{ "CHF" }}</span
                        >{{ value
                        }}<span v-if="index == 'total_success_data'">{{
                            "%"
                        }}</span></span
                    >
                </p>
            </div>
        </div>
        <PieChart
            :data="data"
            :label="label"
            :colors="colors"
            :showPercentage="showPercentage"
            :textColors="textColors"
            :showLabel="showLabel"
        ></PieChart>
    </div>
</template>
<script setup>
import { reactive } from "vue";
import PieChart from "./Chart.vue";

const props = defineProps({
    showLabel: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "",
    },
    label: {
        type: Array,
        default: () => [],
    },
    colors: {
        type: Array,
        default: () => [],
    },
    textColors: {
        type: [Array, String],
        default: "white",
    },
    data: {
        type: Array,
        default: [],
    },
    total: {
        type: Object,
        default: {},
    },
    showPercentage: {
        default: true,
    },
});
</script>

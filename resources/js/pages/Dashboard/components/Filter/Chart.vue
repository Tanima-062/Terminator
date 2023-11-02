<template>
    <div class="chart-box">
        <Pie
            :options="chartOptions"
            :data="chartData"
            :id="chartId"
            :dataset-id-key="datasetIdKey"
            :plugins="plugins"
            :css-classes="cssClasses"
            :styles="styles"
            :width="width"
            :height="height"
        />
    </div>
    <div class="labels grid grid-cols-2 gap-4 mt-14">
        <div class="left">
            <div
                class="labels-container"
                v-for="(item, index) in leftItems"
                :key="index"
            >
                <div
                    class="circle"
                    :style="{
                        background:
                            chartData.datasets[0].backgroundColor[index],
                    }"
                ></div>
                <div class="label">
                    {{ $t(item) }}
                </div>
            </div>
        </div>
        <div class="right">
            <div
                class="labels-container"
                v-for="(item, index) in rightItems"
                :key="index"
            >
                <div
                    class="circle"
                    :style="{
                        background:
                            chartData.datasets[0].backgroundColor[
                                leftItems.length + index
                            ],
                    }"
                ></div>
                <div class="label">
                    {{ $t(item) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from "vue-chartjs";
import DataLabels from "chartjs-plugin-datalabels";

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from "chart.js";
import { useUserStore } from "@/store/user";

const { user } = useUserStore();

ChartJS.register(Title, Legend, ArcElement, CategoryScale);

export default {
    name: "PieChart",
    components: {
        Pie,
    },
    props: {
        chartId: {
            type: String,
            default: "pie-chart",
        },
        datasetIdKey: {
            type: String,
            default: "label",
        },
        width: {
            type: Number,
            default: 300,
        },
        height: {
            type: Number,
            default: 300,
        },
        cssClasses: {
            default: "",
            type: String,
        },
        styles: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Array,
            default: () => [DataLabels],
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
            type: [String, Array],
            default: "white",
        },
        data: {
            type: Array,
            default: () => [],
        },
        showPercentage: {
            default: true,
        },
        showLabel: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        chartData() {
            return {
                labels: this.label,
                datasets: [
                    {
                        datalabels: {
                            anchor: "end",
                            clamp: true,
                            align: "start",
                            formatter: (value, ctx) => {
                                if (this.showPercentage) {
                                    const total = this.data.reduce(
                                        (total, cur) => total + cur,
                                        0
                                    );
                                    const percentage = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);

                                    value = this.showLabel
                                        ? "CHF " + value
                                        : value;
                                    const display = [value, `${percentage} %`];
                                    return display;
                                }
                                return value;
                            },
                            color: this.textColors,
                            font: {
                                family: "Inter",
                                style: "normal",
                                size: "14px",
                                weight: "500",
                            },
                        },
                        backgroundColor: this.colors,
                        data: this.data,
                    },
                ],
            };
        },
        leftItems() {
            return this.label.slice(0, this.countElement());
        },
        rightItems() {
            return this.label.slice(this.countElement());
        },
    },
    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                onClick: (evt, item) => {
                    this.clickPieSlic(evt, item);
                },
            },
        };
    },

    methods: {
        countElement() {
            return Math.ceil(this.label.length / 2);
        },

        clickPieSlic() {
            if (user.type !== "internal_user" && user.type !== "broker_user") {
                const query = { ...this.$route.query };
                this.$router.push({
                    name: "contact-data-records-all-index",
                    query,
                });
            }
        },
    },
};
</script>

<style scoped>
#pie-chart label {
    display: block;
    color: red !important;
}
.labels {
    max-height: 200px;
    overflow-y: auto;
}
.labels-container {
    display: flex;
    align-items: center;
    margin: 20px 0;
}

.label {
    font-family: "Inter";
    font-style: normal;
    font-weight: 500;
    font-size: 12px;
    line-height: 15px;
    color: #636363;
    margin-left: 5px;
    letter-spacing: 0.02em;
}
.circle {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
</style>

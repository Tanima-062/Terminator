<template>
    <div class="content px-6">
        <div class="dashboard">
            <div class="overview__header--title">
                <h3>{{ $t("Dashboard") }}</h3>
            </div>
            <div>
                <Filters>
                    <ButtonFilter
                        label="Organisationselemente"
                        v-model="filters.organization_elements_all"
                        :options="organization_elements_options"
                        column="division"
                        :searchable="false"
                        :allselectable="true"
                        :statusColor="true"
                        placeholder="Search by first or last name"
                        v-if="user.type == 'company_admin'"
                    />
                    <ButtonFilter
                        label="User role"
                        column="roles_id"
                        v-model="filters.roles_id"
                        :options="formatedRoles"
                        v-if="user.type == 'company_admin'"
                    />
                    <ButtonFilter
                        label="Internal Users"
                        v-model="filters.internal_users"
                        :searchables="['label']"
                        :options="users_options"
                        :searchable="true"
                        :allselectable="true"
                        :statusColor="true"
                        placeholder="Search by first or last name"
                        v-if="user.type == 'company_admin'"
                    />
                    <ButtonFilter
                        label="intermediary"
                        v-model="filters.broker_users"
                        :searchables="['label']"
                        :options="users_options"
                        :searchable="false"
                        :allselectable="true"
                        :statusColor="true"
                        v-if="
                            user.type == 'broker_user' && user.role == 'Admin'
                        "
                    />
                    <ButtonFilterDateRange
                        label="Date"
                        :start_date="filters.start_date"
                        :end_date="filters.end_date"
                        @onApply="onApply"
                        @reset="reset"
                    />
                </Filters>
            </div>
            <div class="">
                <div
                    class="flex gap-28 m-12"
                    :class="{
                        'justify-center':
                            user.type !== 'internal_user' &&
                            user.type !== 'broker_user',
                    }"
                >
                    <ChartColumn
                        :title="'Performance'"
                        :data="
                            performance_chart_data.performance_count_per_condition
                        "
                        :label="
                            performance_chart_data.performance_condition_label
                        "
                        :colors="
                            performance_chart_data.performance_condition_label_color
                        "
                        :textColors="
                            performance_chart_data.performance_condition_text_color
                        "
                        :total="
                            user.type == 'broker_user' && user.role == 'Admin'
                                ? total_performance_broker
                                : total_performance
                        "
                    ></ChartColumn>
                    <ChartColumn
                        :title="'Cost'"
                        :showLabel="true"
                        :data="cost_chart_data.cost_count_per_condition"
                        :label="cost_chart_data.cost_condition_label"
                        :colors="cost_chart_data.cost_condition_label_color"
                        :textColors="cost_chart_data.cost_condition_text_color"
                        :total="total_cost"
                        v-if="user.type == 'company_admin'"
                    ></ChartColumn>
                </div>
                <div class="flex justify-center gap-28 m-12 mt-32">
                    <ChartColumn
                        :title="'Success'"
                        :showLabel="true"
                        :data="success_chart_data.success_count_per_condition"
                        :label="success_chart_data.success_condition_label"
                        :colors="
                            success_chart_data.success_condition_label_color
                        "
                        :textColors="
                            success_chart_data.success_condition_text_color
                        "
                        :total="total_success"
                        v-if="user.type == 'company_admin'"
                    ></ChartColumn>
                    <ChartColumn
                        :title="'Profit'"
                        :showLabel="true"
                        :data="profit_chart_data.profit_count_per_condition"
                        :label="profit_chart_data.profit_condition_label"
                        :colors="profit_chart_data.profit_condition_label_color"
                        :textColors="
                            profit_chart_data.profit_condition_text_color
                        "
                        :total="total_profit"
                        v-if="user.type == 'company_admin'"
                    ></ChartColumn>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Filters from "./components/Filter/Filters.vue";
import ButtonFilter from "./components/Filter/ButtonFilter.vue";
import ButtonFilterDateRange from "./components/Filter/ButtonFilterDateRange.vue";
import { reactive, ref } from "@vue/reactivity";
import ChartColumn from "./components/Filter/ChartColumn.vue";
import {
    inject,
    onBeforeMount,
    onMounted,
    onBeforeUpdate,
    onUpdated,
    onBeforeUnmount,
    onUnmounted,
    onActivated,
    onDeactivated,
    onErrorCaptured,
    watch,
} from "@vue/runtime-core";
import { storeToRefs } from "pinia";
import { companyRolesStore } from "@/store/company_roles.js";
import { useRoute, useRouter } from "vue-router";
import { useUserStore } from "@/store/user";
import { indexOf } from "lodash";

const vueRoute = useRoute();
const router = useRouter();
const { user } = useUserStore();

const { formatedRoles } = storeToRefs(companyRolesStore());

let organization_elements = ref([]);
let roles = ref([]);
let internal_users = ref([]);

let organization_elements_options = ref([]);
let users_options = ref([]);

const total_performance = reactive({
    label: {
        total_work_steps: 0,
        total_contact_data: 0,
    },
});
const total_performance_broker = reactive({
    label: {
        total_work_steps: 0,
    },
});

const total_cost = reactive({
    label: {
        total_cost_data: 0,
        total_contact_data: 0,
    },
});

const total_success = reactive({
    label: {
        total_success_data: 0,
        total_contact_data: 0,
    },
});
const total_profit = reactive({
    label: {
        total_profit_data: 0,
        total_contact_data: 0,
    },
});

let performance_chart_data = ref({});
let cost_chart_data = ref({});
let success_chart_data = ref({});
let profit_chart_data = ref({});

const filters = reactive({
    start_date: null,
    end_date: null,
    organization_elements_all: [],
    roles_id: [],
    internal_users: [],
    broker_users: [],
    // search: null
});

const onApply = (e) => {
    filters.start_date = e.start_date;
    filters.end_date = e.end_date;
};
const reset = () => {
    filters.start_date = null;
    filters.end_date = null;
};

watch(
    () => filters,
    (currentValue) => {
        let filtersValues = { ...vueRoute.query };
        delete filtersValues.page;

        Object.keys(filters).forEach(function (key, index) {
            delete filtersValues[key];
            if (
                filters[key] &&
                filters[key].length &&
                typeof filters[key] == "object"
            ) {
                filtersValues[key] = filters[key].join(",");
            }
        });

        if (filters["start_date"]) {
            filtersValues["start_date"] = filters["start_date"];
        }
        if (filters["end_date"]) {
            filtersValues["end_date"] = filters["end_date"];
        }

        router.push({ query: { ...filtersValues } });
    },
    { deep: true }
);

watch(
    () => vueRoute.fullPath,
    async () => {
        getChardData();
        if (user.type == "company_admin") {
            getInternalUser();
        } else if (user.type == "broker_user" && user.role == "Admin") {
            getBrokerlUser();
        }
    }
);

const getOrganizationElement = async () => {
    const { data } = await axios.get(
        route("organization-elements.all", { _query: {} })
    );
    organization_elements_options.value = data.data.map(
        ({ id: value, name: label, ...item }) => ({ value, label, item })
    );
};

const getInternalUser = async () => {
    const { data } = await axios.get(
        route("internal-users.active-inactive", { _query: vueRoute.query })
    );

    users_options.value = data.data.map(
        ({ id: value, user: { full_name }, user: item }) => ({
            value,
            label: full_name,
            item,
        })
    );
};
const getBrokerlUser = async () => {
    const { data } = await axios.get(
        route("brokers.get-itermidary", { _query: vueRoute.query })
    );

    users_options.value = data.map(
        ({ id: value, user: { full_name }, user: item }) => ({
            value,
            label: full_name,
            item,
        })
    );
};

const getChardData = async (params = {}) => {
    const { data } = await axios.get(
        route("dashboard.index", { _query: vueRoute.query })
    );

    total_performance.label.total_work_steps = data.total_work_steps;
    total_performance.label.total_contact_data = data.total_contact_data;
    performance_chart_data.value = data.performance_chart_data;

    cost_chart_data.value = data.cost_chart_data;
    total_cost.label.total_cost_data = data.total_cost_data;
    total_cost.label.total_contact_data = data.total_contact_data;

    total_success.label.total_success_data = data.total_success_data;
    total_success.label.total_contact_data = data.total_contact_data;
    success_chart_data.value = data.success_chart_data;

    total_profit.label.total_profit_data = data.total_profit_data;
    total_profit.label.total_contact_data = data.total_contact_data;
    profit_chart_data.value = data.profit_chart_data;

    if (
        data.performance_chart_data.performance_condition_label.includes(
            "Positive completed"
        )
    ) {
        const total_contact = total_performance.label.total_contact_data;
        const positive_completed =
            data.performance_chart_data.performance_condition_label.indexOf(
                "Positive completed"
            );
        const positive_complete_count =
            performance_chart_data.value.performance_count_per_condition[
                positive_completed
            ];
        total_performance.label.performance_positive_completed =
            Math.ceil((positive_complete_count * 100) / total_contact) + "%";
        if (user.type == "broker_user" && user.role == "Admin") {
            total_performance_broker.label.total_work_steps =  data.total_work_steps;
        }
    }
};

const getQueryParamFromUrl = () => {
    const queries = vueRoute.query;
    filters.start_date = queries["start_date"] ?? null;
    filters.end_date = queries["end_date"] ?? null;

    Object.keys(filters).forEach(function (key, index) {
        if (filters[key] && typeof filters[key] == "object" && queries[key]) {
            filters[key] = queries[key]
                .toString()
                .split(",")
                .map((value) => parseInt(value));
        }
    });
};
getQueryParamFromUrl();

onMounted(() => {
    getChardData();
    getOrganizationElement();
    if (user.type == "company_admin") {
        getInternalUser();
    } else if (user.type == "broker_user" && user.role == "Admin") {
        getBrokerlUser();
    }
});
</script>

<style lang="scss" scoped></style>

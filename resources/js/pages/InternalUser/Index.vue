<template>
    <div class="content px-6">
        <div class="overview__header">
            <div class="overview__header--title">
                <h3>{{ $t("Internal Users") }}</h3>
            </div>
            <div class="overview__header--content flex justify-between">
                <div class="overview__header--left w-[868px]">
                    <Search
                        :placeholder="'Search first name, last name, email address or phone number'"
                    />
                </div>
                <div class="overview__header--right ml-auto flex gap-[22px]">
                    <div class="w-[351px]">
                        <AddNewButton
                            class="!shadow-none w-full"
                            :route="{ name: 'internal-user-create' }"
                            v-if="hasPermission('internal-user:edit')"
                            >{{ $t("Add internal user") }}</AddNewButton
                        >
                    </div>
                    <div class="w-[126px]">
                        <Filters
                            @apply="applyFilter"
                            @reset="resetFilter"
                            width="618px"
                        >
                            <div class="grid grid-cols-2 gap-[30px] mb-10">
                                <template v-if="hierarchy_elements.length">
                                    <MultiSelectFilter
                                        valueKey="id"
                                        labelKey="name"
                                        v-for="(
                                            hierarchy, index
                                        ) in hierarchy_elements"
                                        :key="index"
                                        :indexKey="index"
                                        :filter="true"
                                        v-model="
                                            hirarchy_with_elements.hirarchy_organization_elements.find(
                                                ({ hierarchy_id }) =>
                                                    hierarchy_id == hierarchy.id
                                            ).organization_elements
                                        "
                                        :options="
                                            hierarchy.organization_elements.filter(
                                                ({ internal_users }) =>
                                                    internal_users.length > 0
                                            )
                                        "
                                        :placeholder="`${hierarchy.name} ${$t(
                                            'Select'
                                        )}`"
                                        :label="hierarchy.name"
                                        labelClass="label__class"
                                        placeholderClass="text-[13px]"
                                        @onUpdate="
                                            (value) => {
                                                set_oraganization_element(
                                                    value,
                                                    hierarchy.id
                                                );
                                            }
                                        "
                                    />
                                </template>
                                <template v-if="hierarchy_none_elements.length">
                                    <MultiSelectFilter
                                        valueKey="id"
                                        labelKey="name"
                                        v-for="(
                                            hierarchy, index
                                        ) in hierarchy_none_elements"
                                        :key="index"
                                        :indexKey="index"
                                        :filter="true"
                                        v-model="
                                            hirarchy_none.hirarchy_none_organization_elements.find(
                                                ({ hierarchy_id }) =>
                                                    hierarchy_id == hierarchy.id
                                            ).organization_elements
                                        "
                                        :options="
                                            hierarchy.organization_elements.filter(
                                                ({ internal_users }) =>
                                                    internal_users.length > 0
                                            )
                                        "
                                        :placeholder="`${hierarchy.name} ${$t(
                                            'Select'
                                        )}`"
                                        :label="hierarchy.name"
                                        labelClass="label__class"
                                        placeholderClass="text-[13px]"
                                        @onUpdate="
                                            (value) => {
                                                set_none_oraganization_element(
                                                    value,
                                                    hierarchy.id
                                                );
                                            }
                                        "
                                    />
                                </template>

                                <MultiSelectFilter
                                    label="Allignment"
                                    placeholder="Select allignment"
                                    labelClass="label__class"
                                    v-model="filters.alignment_id"
                                    :options="
                                        filterOptions.alignment_id.data.map(
                                            (alignments) => ({
                                                value: alignments.value,
                                                label: allignment.find(
                                                    (item) =>
                                                        item.value ==
                                                        alignments.value
                                                ).label,
                                            })
                                        )
                                    "
                                    placeholderClass="text-[13px]"
                                    @opened="getOptionData('alignment_id')"
                                    @scrolled="
                                        getNextPageOptionData('alignment_id')
                                    "
                                />
                            </div>
                            <h1
                                class="text-[#AB326F] font-inter font-semibold mb-6 text-[16px]"
                            >
                                {{ $t("Competence") }}
                            </h1>

                            <div class="flex gap-[30px] filter_row mb-[30px]">
                                <MultiSelectFilter
                                    v-model="filters.lang_code"
                                    label="Language"
                                    placeholder="Select Sprache"
                                    labelClass="label__class"
                                    class="w-[300px]"
                                    :options="
                                        filterOptions.lang_code.data.map(
                                            (language) => ({
                                                value: language.value,
                                                label: languagesPlugin.getName(
                                                    language.value,
                                                    user.language.code
                                                ),
                                            })
                                        )
                                    "
                                    placeholderClass="text-[13px]"
                                    @opened="getOptionData('lang_code')"
                                    @scrolled="
                                        getNextPageOptionData('lang_code')
                                    "
                                />
                                <MultiSelectFilter
                                    label="Other competence"
                                    placeholder="Select other competence"
                                    v-model="filters.other_competence"
                                    :options="
                                        filterOptions.other_competence.data
                                    "
                                    optionsClass="w-full right-0 text-[16px] font-semibold"
                                    class="w-[300px] self-end"
                                    labelClass="label__class"
                                    placeholderClass="text-[13px]"
                                    @opened="getOptionData('other_competence')"
                                    @scrolled="
                                        getNextPageOptionData(
                                            'other_competence'
                                        )
                                    "
                                />
                            </div>
                            <div class="flex gap-[30px] filter_row mb-[30px]">
                                <MultiSelectFilter
                                    label="Label"
                                    placeholder="Select level"
                                    v-model="filters.level"
                                    :options="filterOptions.level.data"
                                    optionsClass="w-full right-0 text-[16px] font-semibold"
                                    labelClass="label__class"
                                    placeholderClass="text-[13px]"
                                    class="w-[300px]"
                                    @opened="getOptionData('level')"
                                    @scrolled="getNextPageOptionData('level')"
                                />

                                <MultiSelectFilter
                                    v-model="filters.competence_status"
                                    label="Competence status"
                                    placeholder="Select status"
                                    :options="
                                        filterOptions.competence_status.data.map(
                                            (data) => ({
                                                label: trans(`${data.label}`),
                                                value: data.value,
                                            })
                                        )
                                    "
                                    optionsClass="w-full right-0 text-[16px] font-semibold"
                                    labelClass="label__class"
                                    placeholderClass="text-[13px]"
                                    class="w-[300px]"
                                    @opened="getOptionData('competence_status')"
                                    @scrolled="
                                        getNextPageOptionData(
                                            'competence_status'
                                        )
                                    "
                                />
                            </div>
                            <div class="flex gap-[30px] filter_row mb-[30px]">
                                <!---Roles--->
                                <MultiSelectFilter
                                    label="Role"
                                    placeholder="Select role"
                                    :options="
                                        filterOptions.roles_id.data.map(
                                            (data) => ({
                                                label: trans(`${data.label}`),
                                                value: data.value,
                                            })
                                        )
                                    "
                                    v-model="filters.roles_id"
                                    optionsClass="w-full right-0 text-[16px] font-semibold"
                                    labelClass="label__class"
                                    placeholderClass="text-[13px]"
                                    class="w-[300px]"
                                    @opened="getOptionData('roles_id')"
                                    @scrolled="
                                        getNextPageOptionData('roles_id')
                                    "
                                />
                                <MultiSelectFilter
                                    label="Status"
                                    :options="filterOptions.status.data"
                                    v-model="filters.status"
                                    :placeholder="$t('Select Status')"
                                    class="mb-[52px] w-[300px]"
                                    optionsClass="w-full right-0 text-[16px] font-semibold"
                                    labelClass="label__class"
                                    placeholderClass="text-[13px]"
                                    @opened="getOptionData('status')"
                                    @scrolled="getNextPageOptionData('status')"
                                />
                            </div>
                        </Filters>
                    </div>
                </div>
            </div>
        </div>
        <div class="overview__body">
            <overview-table :paginationData="paginationData">
                <template #header>
                    <SortColumn
                        label="Creation Date"
                        columnName="created_at"
                        style="width: 15%"
                    />
                    <SortColumn
                        label="Correspondence Language"
                        columnName="correspondence_language_code"
                        style="width: 20%"
                    />
                    <SortColumn
                        label="Campaign"
                        columnName="campaign"
                        style="width: 20%"
                    />
                    <SortColumn
                        label="Name"
                        columnName="full_name"
                        style="width: 15%"
                    />
                    <SortColumn
                        label="Role"
                        columnName="roles"
                        style="width: 15%"
                    />
                    <SortColumn
                        label="Status"
                        columnName="status"
                        style="width: 15%"
                    />
                    <div style="width: 3%"></div>
                </template>
                <template #body>
                    <TableRowVue
                        v-for="item in overviewData"
                        :key="item.id"
                        :internal_users="item"
                    />
                </template>
            </overview-table>
        </div>
    </div>
</template>
<script setup>
import AddNewButton from "@/components/button/AddNewButton.vue";
import Filters from "./components/Filters.vue";
import Search from "@/components/form/Search.vue";
import SortColumn from "./components/sort/SortTableColumn.vue";
import OverviewTable from "@/components/table/OverviewTable.vue";
import TableRowVue from "./components/TableRow.vue";
import SingleLanguageSelect from "./components/SingleLanguageSelect.vue";
import SingleSelect from "./components/SingleSelect.vue";
import Multiselect from "./components/MultiSelect.vue";

import MultiSelectFilter from "./components/MultiSelectFilter.vue";
import DateRangeSelector from "@/components/form/DateRangeSelector.vue";

import ButtonGradient from "@/components/button/Gradient.vue";

import { reactive, watch, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useUserStore } from "@/store/user.js";
import axios from "axios";
import labels from "@/labels.json";
import status from "@/status.json";
import other_competence from "@/other_competence.json";
import { companyRolesStore } from "@/store/company_roles.js";
import { storeToRefs } from "pinia";
import languagesPlugin from "@cospired/i18n-iso-languages";
import { trans } from "laravel-vue-i18n";
import SingleSelectHierarchy from "./components/SingleSelect.vue";
import allignment from "@/allignment.json";

import { onMounted, inject } from "@vue/runtime-core";

const vueRoute = useRoute();
const router = useRouter();

const overviewData = ref([]);
const paginationData = ref({});
const hierarchy_elements = ref([]);
const hierarchy_none_elements = ref([]);

const { formatedRoles } = storeToRefs(companyRolesStore());

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const $vfm = inject("$vfm");
const hirarchy_with_elements = reactive({
    hirarchy_organization_elements: [],
});
const hirarchy_none = reactive({
    hirarchy_none_organization_elements: [],
});

const filterOptions = reactive({
    status: {
        data: [],
    },
    customer_companies: {
        data: [],
    },
    lang_code: {
        data: [],
    },
    roles_id: {
        data: [],
    },
    other_competence: {
        data: [],
    },
    competence_status: {
        data: [],
    },
    level: {
        data: [],
    },
    alignment_id: {
        data: [],
    },
    dates: {
        start: null,
        end: null,
    },
});

const filters = reactive({
    status: [],
    roles_id: [],
    lang_code: [],
    level: [],
    other_competence: [],
    competence_status: [],
    organization_elements: [],
    hierarchy_none_org: [],
    alignment_id: [],
    // search: null
});

const applyFilter = () => {
    let filtersValues = { ...vueRoute.query };
    //organization_elements
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

    // if (filters.start_date && filters.end_date) {
    //     filtersValues["start_date"] = filters.start_date;
    //     filtersValues["end_date"] = filters.end_date;
    // }
    router.push({ query: { ...filtersValues } });
};

const resetFilter = () => {
    let filtersValues = { ...vueRoute.query };
    delete filtersValues.page;
    hirarchy_with_elements.hirarchy_organization_elements.forEach((item) => {
        item.organization_elements = "";
    });
    hirarchy_none.hirarchy_none_organization_elements.forEach((item) => {
        item.organization_elements = "";
    });

    Object.keys(filters).forEach(function (key, index) {
        delete filtersValues[key];
        if (
            filters[key] &&
            filters[key].length &&
            typeof filters[key] == "object"
        ) {
            filters[key] = [];
        }
    });

    // filters["start_date"] = null;
    // filters["end_date"] = null;

    router.push({ query: { ...filtersValues } });
};

const set_oraganization_element = (value) => {
    if (filters.organization_elements.includes(value)) {
        filters.organization_elements = Object.values(
            filters.organization_elements
        ).filter((key) => {
            return key !== value;
        });
    } else {
        filters.organization_elements = [
            ...filters.organization_elements,
            value,
        ];
    }
};
const set_none_oraganization_element = (value) => {
    if (filters.hierarchy_none_org.includes(value)) {
        filters.hierarchy_none_org = Object.values(
            filters.hierarchy_none_org
        ).filter((key) => {
            return key !== value;
        });
    } else {
        filters.hierarchy_none_org = [...filters.hierarchy_none_org, value];
    }
};

const getQueryParamFromUrl = () => {
    const queries = vueRoute.query;

    Object.keys(filters).forEach(function (key, index) {
        if (filters[key] && typeof filters[key] == "object" && queries[key]) {
            filters[key] = queries[key].toString().split(",");
        }
    });

    // filters["start_date"] = queries["start_date"] ?? null;
    // filters["end_date"] = queries["end_date"] ?? null;
    // filters['search'] = queries['search']?? null;
};

const getData = async () => {
    const { data } = await axios.get(
        route("internal-users.index", { _query: vueRoute.query })
    );
    overviewData.value = data.data;
    paginationData.value = data.meta;
};

const getOptionData = async (column) => {
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

    if (filters.start_date && filters.end_date) {
        filtersValues["start_date"] = filters.start_date;
        filtersValues["end_date"] = filters.end_date;
    }

    filtersValues["column"] = column;

    const { data } = await axios.get(
        route("internal-users.get-filters", { _query: filtersValues })
    );

    filterOptions[column] = data;
};

const getHierarchy = async () => {
    const { data } = await axios.get(
        route("hierarchy-element.organization-elements", {})
    );
    hierarchy_elements.value = data;
    hirarchy_with_elements.organization_elements = [];
    filters.organization_elements = [];
    hierarchy_elements.value.forEach((item) => {
        hirarchy_with_elements.hirarchy_organization_elements.push({
            hierarchy_id: item.id,
            organization_elements: "",
            hierarchy_level: item.hierarchy_level,
        });
    });
};
const getNoneHierarchy = async () => {
    const { data } = await axios.get(
        route("hierarchy-none.organization-elements", {})
    );
    hierarchy_none_elements.value = data;
    hirarchy_none.organization_elements = [];
    filters.hierarchy_none_org = [];
    hierarchy_none_elements.value.forEach((item) => {
        hirarchy_none.hirarchy_none_organization_elements.push({
            hierarchy_id: item.id,
            organization_elements: "",
            hierarchy_level: item.hierarchy_level,
        });
    });
};

const getNextPageOptionData = async (column) => {
    if (
        filterOptions[column]["last_page"] ==
        filterOptions[column]["current_page"]
    ) {
        return;
    }

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

    // if (filters.start_date && filters.end_date) {
    //     filtersValues["start_date"] = filters.start_date;
    //     filtersValues["end_date"] = filters.end_date;
    // }

    filtersValues["column"] = column;
    filtersValues["page"] = filterOptions[column]["current_page"]
        ? filterOptions[column]["current_page"] + 1
        : 1;
    filtersValues["except_filter"] = column;

    const { data } = await axios.get(
        route("internal-users.get-filters", { _query: filtersValues })
    );

    filterOptions[column]["data"] = [
        ...filterOptions[column]["data"],
        ...data.data,
    ];
    filterOptions[column]["current_page"] = filtersValues["page"];
};

watch(
    () => vueRoute.fullPath,
    async () => {
        getData();
    }
);
getQueryParamFromUrl();

// onMounted(() => {
getData();
getHierarchy();
getNoneHierarchy();
// });
</script>

<style lang="scss">
.label__class {
    font-family: "Inter";
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 19px;
    letter-spacing: 0.02em;
    color: #555555;
}
</style>

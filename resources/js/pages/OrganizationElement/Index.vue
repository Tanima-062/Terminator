<template>
  <div class="content px-6">
    <div class="overview__header">
      <div class="overview__header--title">
        <h3>{{ $t('Organizational elements') }}</h3>
      </div>
      <div class="overview__header--content flex justify-between">
        <div class="overview__header--left w-[868px]">
          <Search :placeholder="user.type == 'system_admin' ? 'Search by customer company, organizational element name, user first or last name' : 'Search by organizational element name, user first or last name'" />
        </div>
        <div class="overview__header--right ml-auto flex gap-[22px]">
          <div class="w-[351px]">
            <AddNewButton class="!shadow-none w-full" :route="{ name: 'organization-element-create' }" v-if="hasPermission('organization-element:edit')">{{ $t('Add organizational element') }}</AddNewButton>
          </div>
          <div class="w-[126px]">
            <Filters @apply="applyFilter" @reset="resetFilter">
              <label for="" class="filter__label">{{ $t('Creation Date') }}</label>
              <DateRangeSelector v-model:start_date="filters.start_date" v-model:end_date="filters.end_date" class="mb-[30px]" />
              <MultiSelectFilter
                label="Customer Company"
                :options="filterOptions.customer_companies.data"
                v-model="filters.customer_companies"
                :placeholder="$t('Select Customer Company')"
                v-if="user.type == 'system_admin'"
                class="mb-[30px]"
                @opened="getOptionData('customer_companies')"
                @scrolled="getNextPageOptionData('customer_companies')"
              />
              <MultiSelectFilter
                label="Type"
                labelClass="label__class"
                :options="filterOptions.hierarchy_type.data"
                v-model="filters.hierarchy_type"
                :placeholder="$t('Select type')"
                class="mb-[52px]"
                @opened="getOptionData('hierarchy_type')"
                @scrolled="getNextPageOptionData('hierarchy_type')"
              />
              <MultiSelectFilter
                label="Responsible:slashr"
                labelClass="label__class"
                :options="filterOptions.responsible_users.data"
                v-model="filters.responsible_users"
                placeholder="Responsible:slashr Select"
                class="mb-[52px]"
                @opened="getOptionData('responsible_users')"
                @scrolled="getNextPageOptionData('responsible_users')"
              />
              <MultiSelectFilter
                label="Status"
                labelClass="label__class"
                :options="filterOptions.status.data"
                v-model="filters.status"
                :placeholder="$t('Select Status')"
                class="mb-[52px]"
                @opened="getOptionData('status')"
                @scrolled="getNextPageOptionData('status')"
              />
            </Filters>
          </div>
        </div>
      </div>
    </div>
    <div class="overview__body">
      <overview-table :paginationData="paginationData" headerClass="py-[22px] gap-x-2">
        <template #header>
          <SortColumn label="Creation Date" columnName="created_at" class="w-[15%]" :class="{ 'w-[12%]': user.type == 'system_admin' }" />
          <SortColumn label="Customer Company" columnName="customer_company_name" class="w-[15%]" v-if="user.type == 'system_admin'" />
          <SortColumn label="Name" columnName="name" class="w-[15%]" :class="{ 'w-[13%]': user.type == 'system_admin' }" />
          <SortColumn label="Type" columnName="hierarchy_name" class="w-[15%]" />
          <SortColumn label="Responsible:slashr" columnName="responsible_user" class="w-[20%]" :class="{ 'w-[15%]': user.type == 'system_admin' }" />
          <SortColumn label="Status" columnName="status" class="w-[10%]" />
          <div class="w-[20%]"></div>
          <!-- <ButtonGradient class="w-[20%]" @click="showOrganizationElementChart">{{ $t('View organization chart') }}</ButtonGradient> -->

          <div class="spacer w-[5%]" v-if="user.type !== 'system_admin'"></div>
        </template>
        <template #body>
          <TableRowVue v-for="element in organization_elements" :key="element.id" :organization_element="element" />
        </template>
      </overview-table>
    </div>
  </div>
</template>
<script setup>
import AddNewButton from '@/components/button/AddNewButton.vue'
import Filters from '@/components/button/Filters.vue'
import Search from '@/components/form/Search.vue'
import SortColumn from '@/components/sort/SortTableColumn.vue'
import OverviewTable from '@/components/table/OverviewTable.vue'

import TableRowVue from './components/TableRow.vue'
import MultiSelectFilter from '@/components/form/MultiSelectFilter.vue'
import DateRangeSelector from '@/components/form/DateRangeSelector.vue'

import ButtonGradient from '@/components/button/Gradient.vue'

import OrganizationElementsTreeViewModal from './components/OrganizationElementsTreeViewModal.vue'

import { reactive, watch, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/store/user.js'
import axios from 'axios'
import { storeToRefs } from 'pinia'

import { onMounted, inject } from '@vue/runtime-core'

const vueRoute = useRoute()
const router = useRouter()

const organization_elements = ref([])
const paginationData = ref({})

const userStore = useUserStore()
const { user } = storeToRefs(userStore)

const $vfm = inject('$vfm')

const filterOptions = reactive({
  status: {
    data: [],
  },
  customer_companies: {
    data: [],
  },
  hierarchy_type: {
    data: [],
  },
  responsible_users: {
    data: [],
  },
  dates: {
    start: null,
    end: null,
  },
})

const filters = reactive({
  status: [],
  customer_companies: [],
  hierarchy_type: [],
  responsible_users: [],
  start_date: null,
  end_date: null,
  // search: null
})

const applyFilter = () => {
  let filtersValues = { ...vueRoute.query }
  delete filtersValues.page

  Object.keys(filters).forEach(function (key, index) {
    delete filtersValues[key]
    if (filters[key] && filters[key].length && typeof filters[key] == 'object') {
      filtersValues[key] = filters[key].join(',')
    }
  })

  if (filters.start_date && filters.end_date) {
    filtersValues['start_date'] = filters.start_date
    filtersValues['end_date'] = filters.end_date
  }

  router.push({ query: { ...filtersValues } })
}

const resetFilter = () => {
  let filtersValues = { ...vueRoute.query }
  delete filtersValues.page

  Object.keys(filters).forEach(function (key, index) {
    delete filtersValues[key]
    if (filters[key] && filters[key].length && typeof filters[key] == 'object') {
      filters[key] = []
    }
  })
  filters['start_date'] = null
  filters['end_date'] = null

  router.push({ query: { ...filtersValues } })
}

const getQueryParamFromUrl = () => {
  const queries = vueRoute.query

  Object.keys(filters).forEach(function (key, index) {
    if (filters[key] && typeof filters[key] == 'object' && queries[key]) {
      filters[key] = queries[key]
        .toString()
        .split(',')
        .map((i) => (isFinite(i) ? parseInt(i) : i))
    }
  })

  filters['start_date'] = queries['start_date'] ?? null
  filters['end_date'] = queries['end_date'] ?? null
  // filters['search'] = queries['search']?? null;
}

const getData = async () => {
    try {
        const { data } = await axios.get(route('organization-elements.index', { _query: vueRoute.query }))
        organization_elements.value = data.data
        paginationData.value = data.meta

    } catch (error) {
        if(error.response.status == 403){
            router.push({ name: '403' })
        }
    }
}

const getOptionData = async (column) => {
  let filtersValues = { ...vueRoute.query }
  delete filtersValues.page

  Object.keys(filters).forEach(function (key, index) {
    delete filtersValues[key]
    if (filters[key] && filters[key].length && typeof filters[key] == 'object') {
      filtersValues[key] = filters[key].join(',')
    }
  })

  if (filters.start_date && filters.end_date) {
    filtersValues['start_date'] = filters.start_date
    filtersValues['end_date'] = filters.end_date
  }

  filtersValues['column'] = column

  const { data } = await axios.get(route('organization-elements.get-filters', { _query: filtersValues }))

  filterOptions[column] = data

//   console.log(data)
}

const getNextPageOptionData = async (column) => {
  if (filterOptions[column]['last_page'] == filterOptions[column]['current_page']) {
    return
  }

  let filtersValues = { ...vueRoute.query }
  delete filtersValues.page

  Object.keys(filters).forEach(function (key, index) {
    delete filtersValues[key]
    if (filters[key] && filters[key].length && typeof filters[key] == 'object') {
      filtersValues[key] = filters[key].join(',')
    }
  })

  if (filters.start_date && filters.end_date) {
    filtersValues['start_date'] = filters.start_date
    filtersValues['end_date'] = filters.end_date
  }

  filtersValues['column'] = column
  filtersValues['page'] = filterOptions[column]['current_page'] ? filterOptions[column]['current_page'] + 1 : 1
  filtersValues['except_filter'] = column

  const { data } = await axios.get(route('organization-elements.get-filters', { _query: filtersValues }))

  filterOptions[column]['data'] = [...filterOptions[column]['data'], ...data.data]
  filterOptions[column]['current_page'] = filtersValues['page']
}

watch(
  () => vueRoute.fullPath,
  async () => {
    getData()
  }
)

const showOrganizationElementChart = () => {
  return
  $vfm.show({
    component: OrganizationElementsTreeViewModal,
    bind: {
      organization_elements: organization_elements.value,
    },
  })
}

onMounted(() => {
  getQueryParamFromUrl()
  getData()
})
</script>

<style lang="scss">
.label__class {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 19px;
  letter-spacing: 0.02em;
  color: #555555;
}
</style>

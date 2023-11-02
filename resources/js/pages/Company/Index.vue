<template>
    <div class="content px-6">
       <div class="overview__header">
            <div class="overview__header--title">
                <h3>{{ $t('Customer Companies') }}</h3>
            </div>
            <div class="overview__header--content flex justify-between">
                <div class="overview__header--left w-[875px]">
                    <Search placeholder="Search by company ID, name or location, first or last name, email address or phone number of the contact person" />
                </div>
                <div class="overview__header--right ml-auto flex gap-[22px]" >
                    <div class="w-[313px]">
                        <AddNewButton :route="{name: 'customer-company-create'}">{{ $t('Add New Customer Company') }}</AddNewButton>
                    </div>
                    <div class="w-[126px]">
                        <Filters @apply="applyFilter" @reset="resetFilter">
                            <label for="" class="filter__label">{{ $t('Creation Date') }}</label>
                            <DateRangeSelector  v-model:start_date="filters.start_date" v-model:end_date="filters.end_date"  class="mb-[30px]" />
                            <MultiSelectFilter
                                label="Customer Company"
                                :options="filterOptions.customer_companies.data"
                                v-model="filters.customer_companies"
                                :placeholder="$t('Select Customer Company')"
                                class="mb-[30px]"
                                @opened="getOptionData('customer_companies')"
                                @scrolled="getNextPageOptionData('customer_companies')"
                            />
                            <MultiSelectFilter
                                label="Company Location"
                                labelClass="label__class"
                                :options="filterOptions.company_locations.data"
                                v-model="filters.company_locations"
                                :placeholder="$t('Select Company Location')"
                                class="mb-[30px]"
                                @opened="getOptionData('company_locations')"
                                @scrolled="getNextPageOptionData('company_locations')"
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
            <overview-table :paginationData="paginationData">
                <template #header>
                    <SortColumn label="Creation Date" columnName="created_at" style="width:15%" />
                    <SortColumn label="Company ID" columnName="prefix_id" style="width:15%" />
                    <SortColumn label="Company Name" columnName="name" style="width:20%"/>
                    <SortColumn label="Company Alias Name" columnName="alias_name" style="width:20%"/>
                    <SortColumn label="Contact Person" columnName="contact_person_first_name" style="width:15%"/>
                    <SortColumn label="Status" columnName="status" style="width:10%"/>
                    <div style="width:5%"></div>
                </template>
                <template #body>
                    <TableRowVue v-for="item in overviewData" :key="item.id" :customer_company="item" />
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
    import MultiSelect from '../../components/form/MultiSelect.vue'
    import MultiSelectFilter from '../../components/form/MultiSelectFilter.vue'
    import DateRangeSelector from '@/components/form/DateRangeSelector.vue'

    import {reactive, watch, ref} from 'vue'
    import {useRoute, useRouter} from 'vue-router'
    import axios from 'axios'
    import {usePermissionStore} from '@/composables/permission'


    const vueRoute = useRoute();
    const router = useRouter();

    const overviewData = ref([]);
    const paginationData = ref({});
    const permission = usePermissionStore();


    const filterOptions = reactive({
        status:  {
            data: []
        },
        customer_companies:  {
            data:[]
        },
        company_locations:  {
            data: []
        },
        dates: {
            start: null,
            end: null
        }
    })

    const filters = reactive({
        status: [],
        customer_companies: [],
        company_locations: [],
        start_date: null,
        end_date: null,
        // search: null
    })



    const applyFilter = ()=> {
        let filtersValues = {...vueRoute.query};
        delete filtersValues.page;

        Object.keys(filters).forEach(function(key, index) {
            delete filtersValues[key];
            if(filters[key] && filters[key].length && typeof filters[key] == 'object'){
                filtersValues[key] =filters[key].join(',')
            }
        });

        if(filters.start_date && filters.end_date){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        router.push({query: {...filtersValues}})
    }

    const resetFilter = ()=> {
        let filtersValues = {...vueRoute.query};
        delete filtersValues.page;

        Object.keys(filters).forEach(function(key, index) {
            delete filtersValues[key];
            if(filters[key] && filters[key].length && typeof filters[key] == 'object'){
                filters[key] = []
            }
        });
        filters['start_date'] = null;
        filters['end_date'] = null;

        router.push({query: {...filtersValues}})
    }



    const getQueryParamFromUrl =() => {
        const queries = vueRoute.query;

        Object.keys(filters).forEach(function(key, index) {
            if(filters[key] && typeof filters[key] == 'object' && queries[key] ){
                filters[key] = queries[key].toString().split(',').map(i => isFinite(i) ? parseInt(i) : i);
            }
        });

        filters['start_date'] = queries['start_date']?? null;
        filters['end_date'] = queries['end_date']?? null;
        // filters['search'] = queries['search']?? null;
    }


    const getData = async ()=>{

        try {
            const {data} = await axios.get(route('customer-companies.index', {_query: vueRoute.query}))
            overviewData.value = data.data
            paginationData.value = data.meta

        } catch (error) {
            if(error.response.status == 403){
                router.push({ name: '403' })
            }
        }
    }



    const getOptionData = async (column) => {
        let filtersValues = {...vueRoute.query};
        delete filtersValues.page;

        Object.keys(filters).forEach(function(key, index) {
            if(filtersValues[key]){
                delete filtersValues[key];
                if(filters[key]  && filtersValues[key] &&  filters[key].length && typeof filters[key] == 'object'){
                    filtersValues[key] =filters[key].join(',')
                }
            }
        });

        if(filters.start_date && filters.end_date && filtersValues.start_date && filtersValues.end_date ){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        filtersValues['column'] = column

        const {data} = await axios.get(route('customer-companies.get-filters', {_query:filtersValues}))

        filterOptions[column] = data

        // console.log(data)
    }

    const getNextPageOptionData = async (column) => {
        if(filterOptions[column]['last_page'] == filterOptions[column]['current_page']) {

            return;
        }

        let filtersValues = {...vueRoute.query};
        delete filtersValues.page;

        Object.keys(filters).forEach(function(key, index) {
            if(filtersValues[key]){
                delete filtersValues[key];
                if(filters[key] && filters[key].length && typeof filters[key] == 'object'){
                    filtersValues[key] =filters[key].join(',')
                }
            }
        });

        if(filters.start_date && filters.end_date && filtersValues.start_date && filtersValues.end_date ){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        filtersValues['column'] = column
        filtersValues['page'] =  filterOptions[column]['current_page'] ? filterOptions[column]['current_page'] + 1 : 1;
        filtersValues['except_filter'] = column;

        const {data} = await axios.get(route('customer-companies.get-filters', {_query:filtersValues}))

        filterOptions[column]['data'] = [...filterOptions[column]['data'], ...data.data]
        filterOptions[column]['current_page'] =  filtersValues['page']
    }


    watch(
        () => vueRoute.fullPath,
        async () => {
            // console.log('do something!')
            getData()
        }
    );

    getQueryParamFromUrl()
    getData();

    // getOptionData('customer_companies');

    if( !permission.hasAnyPermissions(['customer-companye:view', 'customer-companye:edit',])){
        // console.log('has access')
        router.push({ name: '403' })
    }
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

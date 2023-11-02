<template>
    <div class="content px-6">
       <div class="overview__header mb-[38px]">
            <div class="overview__header--title">
                <h3>{{ $t('Broker & Broker User') }}</h3>
            </div>
            <div class="overview__header--content flex justify-between">
                <div class="overview__header--left w-[875px]">
                    <Search placeholder="Search by first-, last name, email or phone number" />
                </div>
                <div class="overview__header--right ml-auto flex gap-[22px]" >
                    <div class="w-[380px]">
                        <AddNewButton :route="{name: 'broker-user-create'}">{{ $t('Add Broker User') }}</AddNewButton>
                    </div>
                    <div class="w-[126px]">
                        <Filters @apply="applyFilter" @reset="resetFilter">
                            <label for="" class="filter__label">{{ $t('Creation Date') }}</label>
                            <DateRangeSelector  v-model:start_date="filters.start_date" v-model:end_date="filters.end_date"  class="mb-[30px]" />
                            <MultiSelectFilter
                                label="Broker"
                                :options="filterOptions.brokers.data"
                                v-model="filters.brokers"
                                :placeholder="$t('Select Broker')"
                                class="mb-[30px]"
                                @opened="getOptionData('brokers')"
                                @scrolled="getNextPageOptionData('brokers')"
                            />
                            <MultiSelectFilter
                                label="Name"
                                :options="filterOptions.users.data"
                                v-model="filters.users"
                                :placeholder="$t('Select Name')"
                                class="mb-[30px]"
                                @opened="getOptionData('users')"
                                @scrolled="getNextPageOptionData('users')"
                            />
                            <MultiSelectFilter
                                label="Role"
                                labelClass="label__class"
                                :options="filterOptions.roles.data"
                                v-model="filters.roles"
                                :placeholder="$t('Select Role')"
                                class="mb-[30px]"
                                @opened="getOptionData('roles')"
                                @scrolled="getNextPageOptionData('roles')"
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
       <div class="overview__tabs">
            <router-link :to="{name: 'broker-index'}" class="overview__tabs--item">{{ $t('Broker') }}</router-link>
            <div class="overview__tabs--item active">{{ $t('Broker User') }}</div>
       </div>
       <div class="overview__body mt-0 h-[817px]">
            <overview-table :paginationData="paginationData">
                <template #header>
                    <SortColumn label="Creation Date" columnName="created_at" style="width:15%" />
                    <SortColumn label="Broker" columnName="broker" style="width:15%" />
                    <SortColumn label="Correspondence Language" columnName="correspondence_language" style="width:20%"/>
                    <SortColumn label="Name" columnName="name" style="width:20%"/>
                    <SortColumn label="Role" columnName="role" style="width:9%"/>
                    <SortColumn label="Status" columnName="status" style="width:16%"/>
                    <div style="width:5%"></div>
                </template>
                <template #body>
                    <TableRowVue v-for="(item, index) in overviewData" :key="item.id" :broker_user="item" v-model="selectedRows[index]" />
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
    import ButtonGradient from '@/components/button/Gradient.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';

    import {reactive, watch, ref, computed} from 'vue'
    import {useRoute, useRouter} from 'vue-router'
    import axios from 'axios'

    const vueRoute = useRoute();
    const router = useRouter();

    const overviewData = ref([]);
    const paginationData = ref({});

    const filterOptions = reactive({
        status:  {
            data: []
        },
        brokers:  {
            data:[]
        },
        users:  {
            data:[]
        },
        roles:  {
            data: []
        },
        dates: {
            start: null,
            end: null
        }
    })

    const filters = reactive({
        status: [],
        brokers: [],
        users: [],
        roles: [],
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
                //filters[key] = queries[key].toString().split(',')
                filters[key] = queries[key]
                .toString()
                .split(',')
                .map((i) => (isFinite(i) ? parseInt(i) : i))
            }
        });

        filters['start_date'] = queries['start_date']?? null;
        filters['end_date'] = queries['end_date']?? null;
        // filters['search'] = queries['search']?? null;
    }


    const getData = async ()=>{
        const {data} = await axios.get(route('broker-users.index', {_query: vueRoute.query}))
        overviewData.value = data.data
        paginationData.value = data.meta

        const selected = [];
        data.data.forEach(item=> {
            selected.push(false);
        })
        selectedRows.value = selected;

    }



    const getOptionData = async (column) => {
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

        if(filters.start_date && filters.end_date && filtersValues.start_date && filtersValues.end_date){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        filtersValues['column'] = column

        const {data} = await axios.get(route('brokerUsers.get-filters', {_query:filtersValues}))

        filterOptions[column] = data
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

        if(filters.start_date && filters.end_date && filtersValues.start_date && filtersValues.end_date){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        filtersValues['column'] = column
        filtersValues['page'] =  filterOptions[column]['current_page'] ? filterOptions[column]['current_page'] + 1 : 1;
        filtersValues['except_filter'] = column;

        const {data} = await axios.get(route('brokerUsers.get-filters', {_query:filtersValues}))

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

    const selectedRows = ref([]);

    const isAllSelected = computed({
        get: () => selectedRows.value.every((item)=> item),
        set: (value) => {
            if (isAllSelected.value) {
                selectedRows.value = selectedRows.value.map(item=>false)
            } else {
                selectedRows.value = selectedRows.value.map(item=>true)
            }
        }
    })

    getQueryParamFromUrl()
    getData();

    // getOptionData('customer_companies');
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

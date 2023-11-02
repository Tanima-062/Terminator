<template>
    <div class="content px-6 min-w-[calc(100vw_-_320px)] w-[100vw_-_90px]">
       <div class="overview__header mb-[38px]">
            <div class="overview__header--title">
                <h3>{{ $t('Appointments') }}</h3>
            </div>
            <div class="overview__header--content flex justify-between">
                <div class="overview__header--left w-[830px]">
                    <Search placeholder="Search by Contact data record-, appointment ID, first-, last name, mobile phone number, email, street name, zip code or canton" />
                </div>
                <div class="overview__header--right ml-auto flex gap-[14px]">
                    <div class="w-[126px]">
                        <Filters @apply="applyFilter" @reset="resetFilter" width="652px">
                            <div class="filter_tabs">
                                <div class="flex gap-[80px] filter_row mb-[30px]">
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Appointment Date') }}</label>
                                        <DateRangeSelector  class="w-[233px]"  v-model:start_date="filters.termin_start_date" v-model:end_date="filters.termin_end_date" />
                                    </div>
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Appointment Time') }}</label>
                                        <TimeRangeSelector  class="w-[233px]"  v-model:time_start="filters.termin_start_time" v-model:time_end="filters.termin_end_time" />
                                    </div>
                                    <!-- <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Allocation Date') }}</label>
                                        <DateRangeSelector  v-model:start_date="filters.allocation_start_date" v-model:end_date="filters.allocation_end_date" />
                                    </div> -->

                                </div>

                                <div class="flex  gap-x-[80px] flex-wrap filter_row">

                                    <template v-if=" userStore.user.type == 'broker_user' && userStore.user.role == 'Admin' ">
                                        <MultiSelectFilter
                                            label="intermediary"
                                            labelClass="label__class"
                                            :options="filterOptions.intermediaries.data"
                                            v-model="filters.intermediaries"
                                            placeholder="Select intermediary"
                                            class="w-[233px]  mb-[30px]"
                                            @opened="getOptionData('intermediaries')"
                                            @scrolled="getNextPageOptionData('intermediaries')"
                                            placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                        />
                                    </template>


                                    <MultiSelectFilter
                                        label="Canton"
                                        labelClass="label__class"
                                        :options="filterOptions.cantons.data"
                                        v-model="filters.cantons"
                                        :placeholder="$t('Select canton')"
                                        class="w-[233px]"
                                        @opened="getOptionData('cantons')"
                                        @scrolled="getNextPageOptionData('cantons')"
                                        placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                    />
                                <!-- </div> -->
                                <!-- <div class="flex gap-[80px] filter_row mb-[30px]"> -->
                                    <MultiSelectFilter
                                        label="Correspondence Language"
                                        labelClass="label__class"
                                        :options="filterOptions.correspondence_languages.data"
                                        v-model="filters.correspondence_languages"
                                        :placeholder="$t('Select Correspondence language')"
                                        class="w-[233px]  mb-[30px]"
                                        @opened="getOptionData('correspondence_languages')"
                                        @scrolled="getNextPageOptionData('correspondence_languages')"
                                        placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                    />
                                    <MultiSelectFilter
                                        label="Other Languages"
                                        labelClass="label__class"
                                        :options="filterOptions.other_languages.data"
                                        v-model="filters.other_languages"
                                        :placeholder="$t('Select other languages')"
                                        class="w-[233px]  mb-[30px]"
                                        @opened="getOptionData('other_languages')"
                                        @scrolled="getNextPageOptionData('other_languages')"
                                        placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                    />
                                <!-- </div> -->
                                <!-- <div class="flex gap-[80px] filter_row"> -->
                                    <MultiSelectFilter
                                        label="Contact record status"
                                        labelClass="label__class"
                                        :options="filterOptions.contact_record_status.data"
                                        v-model="filters.contact_record_status"
                                        :placeholder="$t('Select status')"
                                        class="w-[233px]  mb-[30px]"
                                        @opened="getOptionData('contact_record_status')"
                                        @scrolled="getNextPageOptionData('contact_record_status')"
                                    />
                                </div>
                            </div>
                            <template #advanced_filter>
                            </template>
                        </Filters>
                    </div>
                </div>
            </div>
       </div>
       <!-- <div class="overview__body mt-0" style="height: initial;"> -->
       <div class="overview__body mt-0" style="height: 817px;">
            <overview-table :paginationData="paginationData" :headerTop="true" bodyStyle="overflow-x: hidden;" >
                <template #header-top>
                    <div class="w-full" >
                        <CheckboxInput
                            v-model="isAllSelected"
                            :checkLabel="isAllSelected ? 'availability_deselect_all' : 'availability_select_all'"
                        />
                    </div>
                    <div class="flex gap-3.5">
                        <AllocateButton class="w-max" :disabled="!enableAllocateButton" @click="showAllocateModal"  v-if="hasPermission('termin.edit')">
                            {{ $t("Allocate Appointment_s") }}
                        </AllocateButton>
                    </div>
                </template>
                <template #header>
                    <div class="w-min flex">
                        <div style="width: 50px;"></div>
                        <SortColumn label="Appointment ID" columnName="appointment_id" style="width:110px;" :style="{width: getWidth(userStore.user, '110px', '130px')}" />
                        <SortColumn label="Appointment Date" columnName="appointment_date" style="width:170px;" :style="{width: getWidth(userStore.user, '170px', '190px')}"/>
                        <SortColumn label="Appointment Time" columnName="appointment_time" style="width:170px;" :style="{width: getWidth(userStore.user, '170px', '190px')}" />
                        <SortColumn label="Allocated to" columnName="allocated_to" style="width:160px;"  :enable="false"  v-if=" userStore.user.type == 'broker_user' && userStore.user.role == 'Admin' " />

                        <SortColumn label="Correspondence Language" columnName="correspondence_language" style="width:225px;" :style="{width: getWidth(userStore.user, '225px', '245px')}"/>
                        <SortColumn label="First Name" columnName="first_name" style="width:160px;" :style="{width: getWidth(userStore.user, '160px', '190px')}" />
                        <SortColumn label="Last Name" columnName="last_name" style="width:160px;" :style="{width: getWidth(userStore.user, '160px', '190px')}"/>

                        <SortColumn label="Status" columnName="contact_record_status" style="width:200px;" :style="{width: getWidth(userStore.user, '200px', '210px')}" />
                        <div style="width:45px;"></div>
                        <!-- <div class="td" style="width: 42px; padding-left: 5px; position: absolute; right: 38px;background: white;color: white;">
                            adfdsf
                        </div> -->
                    </div>
                </template>
                <template #body>
                    <TableRow v-for="(item, index) in overviewData" :key="item.id" :contact_data_record="item" v-model="selectedRows[item.id]" />
                </template>
            </overview-table>
       </div>
    </div>
    <!-- <AllocationModal></AllocationModal> -->
    <CancelAllocationModal></CancelAllocationModal>
    <ConfirmAllocationModal></ConfirmAllocationModal>

</template>
<script setup>
    import AddNewButton from '@/components/button/AddNewButton.vue'
    import Filters from './components/Filters.vue'
    import Search from '@/components/form/Search.vue'
    import SortColumn from '@/components/sort/SortTableColumn.vue'
    // import OverviewTable from './components/OverviewTable.vue'
    import OverviewTable from '@/components/table/OverviewTable.vue'


    import TableRow from './components/TableRow.vue'
    import MultiSelectFilter from '@/components/form/MultiSelectFilter.vue'
    import DateRangeSelector from '@/components/form/DateRangeSelector.vue'
    import TimeRangeSelector from '@/components/form/TimeRangeSelector.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';

    import {reactive, watch, ref, computed, inject, toRaw} from 'vue'
    import {useRoute, useRouter} from 'vue-router'
    import axios from 'axios'
    import AllocateButton from './components/allocations/AllocateButton.vue';
    import AllocationModal from './components/allocations/AllocationModal.vue';
    import CancelAllocationModal from './components/allocations/CancelAllocationModal.vue';
    import ConfirmAllocationModal from './components/allocations/ConfirmAllocationModal.vue';


    const $vfm = inject("$vfm");

    const vueRoute = useRoute();
    const router = useRouter();

    const overviewData = ref([]);
    const paginationData = ref({});

    const filterOptions = reactive({
        intermediaries:  {
            data:[]
        },
        cantons:  {
            data:[]
        },
        correspondence_languages:  {
            data:[]
        },
        other_languages:  {
            data:[]
        },
        contact_record_status:  {
            data:[]
        },
    })

    const filters = reactive({
        intermediaries: [],
        cantons: [],
        correspondence_languages: [],
        other_languages: [],
        contact_record_status: [],


        termin_start_date: null,
        termin_end_date: null,

        termin_start_time: null,
        termin_end_time: null,
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


        if(filters.termin_start_date && filters.termin_end_date){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }
        if(filters.termin_start_time && filters.termin_end_time){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
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

        filters['termin_start_date'] = null;
        filters['termin_end_date'] = null;

        filters['termin_start_time'] = null;
        filters['termin_end_time'] = null;


        router.push({query: {...filtersValues}})
    }



    const getQueryParamFromUrl =() => {
        const queries = vueRoute.query;

        Object.keys(filters).forEach(function(key, index) {
            if(filters[key] && typeof filters[key] == 'object' && queries[key] ){
                filters[key] = queries[key].toString().split(',')
            }
        });

        filters['termin_start_date'] = queries['termin_start_date']?? null;
        filters['termin_end_date'] = queries['termin_end_date']?? null;

        filters['termin_start_time'] = queries['termin_start_time']?? null;
        filters['termin_end_time'] = queries['termin_end_time']?? null;
    }


    const getData = async ()=>{
        const {data} = await axios.get(route('termin.index', {_query: vueRoute.query}))
        overviewData.value = data.data
        paginationData.value = data.meta

        const selected = {};
        data.data.forEach(item=> {
            if(item.contact_record_status != 'Duplicate' && item.contact_record_status != 'Check Duplicate'){
                selected[item.id] = false
            }
            // selected.push(false);
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

        if(filters.termin_start_date && filters.termin_end_date  && filtersValues.termin_start_date && filtersValues.termin_end_date ){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }

        if(filters.termin_start_time && filters.termin_end_time  && filtersValues.termin_start_time && filtersValues.termin_end_time ){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
        }

        filtersValues['column'] = column

        const {data} = await axios.get(route('termins.get-filters', {_query:filtersValues}))

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


        if(filters.termin_start_date && filters.termin_end_date && filtersValues.termin_start_date && filtersValues.termin_end_date ){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }

        if(filters.termin_start_time && filters.termin_end_time && filtersValues.termin_start_time && filtersValues.termin_end_time ){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
        }

        filtersValues['column'] = column
        filtersValues['page'] =  filterOptions[column]['current_page'] ? filterOptions[column]['current_page'] + 1 : 1;
        filtersValues['except_filter'] = column;

        const {data} = await axios.get(route('termins.get-filters', {_query:filtersValues}))

        filterOptions[column]['data'] = [...filterOptions[column]['data'], ...data.data]
        filterOptions[column]['current_page'] =  filtersValues['page']
    }


    watch(
        () => vueRoute.fullPath,
        async (newValue, oldValue) => {
            if(vueRoute.name == 'termins-index') {
                getData()
            }
        }
    );
    const selectedRows = ref({});
    const isAllSelected = computed({
        get: () => {
            return Object.values(selectedRows.value).every((item)=> item)
        },
        set: (value) => {
            const allSelectdRows = selectedRows.value;
            if (isAllSelected.value) {
                Object.keys(allSelectdRows).forEach(key=>allSelectdRows[key] = false)
            } else {
                Object.keys(allSelectdRows).forEach(key=>allSelectdRows[key] = true)
            }
        }
    })

    const enableAllocateButton = computed(()=> {
        return Object.values(selectedRows.value).some((item)=>item);
        // return true;
    })

    const showAllocateModal = ()=> {
        const options = {
            component: AllocationModal,
            bind: {
                contact_data_records: selectedRows.value,
                type: 'appointment'
            },
        }
        $vfm.show(options)

        // $vfm.show("allocation-modal", {
        //     contact_data_records: selectedRows.value,
        // });
    }

    const getWidth = (user, adminWidth, intermediaryWidth)=> {
        // v-if=" userStore.user.type == 'broker_user' && userStore.user.role == 'Admin' "
        if(user.role == 'Admin') {
            return adminWidth
        }

        return intermediaryWidth;
    }

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

    // .overview__table--header {
    //     overflow-x: scroll;
    //     gap: 25px;



    //     -ms-overflow-style: none;  /* IE and Edge */
    //     scrollbar-width: none;  /* Firefox */
    // }

    // /* Hide scrollbar for Chrome, Safari and Opera */
    // .overview__table--header::-webkit-scrollbar {
    //     display: none;
    // }

    // .overview__table--body {
    //     overflow: auto;
    // }
</style>

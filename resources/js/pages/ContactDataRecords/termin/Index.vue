<template>
    <div class="content px-6 min-w-[calc(100vw_-_320px)] w-[100vw_-_90px]">
       <div class="overview__header mb-[38px]">
            <div class="overview__header--title">
                <!-- <h3>{{ $t('Contact Data Records') }}</h3> -->
                <template v-if=" userStore.user.type == 'internal_user' && userStore.user.role == 'Quality controller' ">
                    <h3 v-if="userStore.user.alignment.includes('1') && userStore.user.alignment.includes('2')">
                        {{ $t('Contact Data Records') }}
                    </h3>
                    <h3 v-else-if="userStore.user.alignment.includes('2')">{{ $t('Appointments') }}</h3>
                </template>
                <template v-else>
                    <h3>{{ $t('Contact Data Records') }}</h3>
                </template>
            </div>
            <div class="overview__header--content flex justify-between">
                <div class="overview__header--left w-[830px]">
                    <Search placeholder="Search by Contact data record-, appointment ID, first-, last name, mobile phone number, email, street name, zip code or canton" />
                </div>
                <div class="overview__header--right ml-auto flex gap-[14px]">
                    <div class="w-[313px]" v-if="hasPermission('contact-data-record:edit')">
                        <AddNewButton :route="{name: 'contact-data-records-create'}">{{ $t('Add New Contact Data Record') }}</AddNewButton>
                    </div>
                    <div class="w-[141px]" v-if="hasPermission('contact-data-record:import')">
                        <!-- <ButtonGradient>{{ $t('Import') }}</ButtonGradient> -->
                        <ImportButton></ImportButton>
                    </div>
                    <div class="w-[126px]">
                        <Filters @apply="applyFilter" @reset="resetFilter" width="1302px">
                            <div class="filter_tabs">
                                <div class="flex gap-[90px] filter_row mb-[30px]">
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Creation Date') }}</label>
                                        <DateRangeSelector  v-model:start_date="filters.start_date" v-model:end_date="filters.end_date" />
                                    </div>
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Appointment Date') }}</label>
                                        <DateRangeSelector  v-model:start_date="filters.termin_start_date" v-model:end_date="filters.termin_end_date" />
                                    </div>
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Appointment Time') }}</label>
                                        <TimeRangeSelector   v-model:time_start="filters.termin_start_time" v-model:time_end="filters.termin_end_time" />
                                    </div>
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Allocation Date') }}</label>
                                        <DateRangeSelector  v-model:start_date="filters.allocation_start_date" v-model:end_date="filters.allocation_end_date" />
                                    </div>

                                </div>
                                <div class="flex gap-[90px] filter_row mb-[30px]">

                                    <MultiSelectFilter
                                        label="Internal User"
                                        labelClass="label__class"
                                        :options="filterOptions.internal_users.data"
                                        v-model="filters.internal_users"
                                        :placeholder="$t('Select internal users')"
                                        class=" w-[233px]"
                                        @opened="getOptionData('internal_users')"
                                        @scrolled="getNextPageOptionData('internal_users')"
                                    />
                                    <MultiSelectFilter
                                        label="Source"
                                        labelClass="label__class"
                                        :options="filterOptions.sources.data"
                                        v-model="filters.sources"
                                        :placeholder="$t('Select source')"
                                        class="w-[233px]"
                                        @opened="getOptionData('sources')"
                                        @scrolled="getNextPageOptionData('sources')"
                                    />
                                </div>
                                <div class="flex gap-[90px] filter_row mb-[30px]">
                                    <MultiSelectFilter
                                        label="VariableA"
                                        labelClass="label__class"
                                        :options="filterOptions.variableA.data"
                                        v-model="filters.variableA"
                                        :placeholder="$t('Select variableA')"
                                        class="w-[233px]"
                                        @opened="getOptionData('variableA')"
                                        @scrolled="getNextPageOptionData('variableA')"
                                    />
                                    <div class="w-[233px]">
                                        <label for="" class="filter__label">{{ $t('Year') }}</label>
                                        <YearRangeSelector  v-model:year_start="filters.year_start" v-model:year_end="filters.year_end" />
                                    </div>

                                    <MultiSelectFilter
                                        label="Canton"
                                        labelClass="label__class"
                                        :options="filterOptions.cantons.data"
                                        v-model="filters.cantons"
                                        :placeholder="$t('Select canton')"
                                        class="w-[233px]"
                                        @opened="getOptionData('cantons')"
                                        @scrolled="getNextPageOptionData('cantons')"
                                    />
                                    <MultiSelectFilter
                                        label="Region"
                                        labelClass="label__class"
                                        :options="filterOptions.regions.data"
                                        v-model="filters.regions"
                                        :placeholder="$t('Select region')"
                                        class="w-[233px]"
                                        @opened="getOptionData('regions')"
                                        @scrolled="getNextPageOptionData('regions')"
                                    />
                                </div>
                                <div class="flex gap-[90px] filter_row mb-[30px]">
                                    <MultiSelectFilter
                                        label="Correspondence Language"
                                        labelClass="label__class"
                                        :options="filterOptions.correspondence_languages.data"
                                        v-model="filters.correspondence_languages"
                                        :placeholder="$t('Select Correspondence language')"
                                        class="w-[233px]"
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
                                        class="w-[233px]"
                                        @opened="getOptionData('other_languages')"
                                        @scrolled="getNextPageOptionData('other_languages')"
                                    />
                                    <MultiSelectFilter
                                        label="Campaign"
                                        labelClass="label__class"
                                        :options="filterOptions.campaigns.data"
                                        v-model="filters.campaigns"
                                        :placeholder="$t('Select campaign')"
                                        class="w-[233px]"
                                        @opened="getOptionData('campaigns')"
                                        @scrolled="getNextPageOptionData('campaigns')"
                                    />
                                    <MultiSelectFilter
                                        label="Saving"
                                        labelClass="label__class"
                                        :options="filterOptions.saves.data"
                                        v-model="filters.saves"
                                        :placeholder="$t('Select savings')"
                                        class="w-[233px]"
                                        @opened="getOptionData('saves')"
                                        @scrolled="getNextPageOptionData('saves')"
                                    />
                                </div>
                                <div class="flex gap-[90px] filter_row mb-[30px]">
                                    <MultiSelectFilter
                                        label="Health Insurance"
                                        labelClass="label__class"
                                        :options="filterOptions.health_insurances.data"
                                        v-model="filters.health_insurances"
                                        :placeholder="$t('Select health insurance')"
                                        class="w-[233px]"
                                        @opened="getOptionData('health_insurances')"
                                        @scrolled="getNextPageOptionData('health_insurances')"
                                    />
                                    <MultiSelectFilter
                                        label="3rd Pillar"
                                        labelClass="label__class"
                                        :options="filterOptions.third_pillers.data"
                                        v-model="filters.third_pillers"
                                        :placeholder="$t('Select 3rd pillar')"
                                        class="w-[233px]"
                                        @opened="getOptionData('third_pillers')"
                                        @scrolled="getNextPageOptionData('third_pillers')"
                                    />
                                    <MultiSelectFilter
                                        label="Contact desired"
                                        labelClass="label__class"
                                        :options="filterOptions.contact_desireds.data"
                                        v-model="filters.contact_desireds"
                                        :placeholder="$t('Select Contact desired')"
                                        class="w-[233px]"
                                        @opened="getOptionData('contact_desireds')"
                                        @scrolled="getNextPageOptionData('contact_desireds')"
                                        placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                    />
                                    <MultiSelectFilter
                                        label="Control status (Lead)"
                                        labelClass="label__class"
                                        :options="filterOptions.leads.data"
                                        v-model="filters.leads"
                                        :placeholder="$t('Select control status')"
                                        class="w-[233px]"
                                        @opened="getOptionData('leads')"
                                        @scrolled="getNextPageOptionData('leads')"
                                    />
                                </div>
                                <div class="flex gap-[90px] filter_row">
                                    <MultiSelectFilter
                                        label="Feedback"
                                        labelClass="label__class"
                                        :options="filterOptions.feedbacks.data"
                                        v-model="filters.feedbacks"
                                        :placeholder="$t('Select feedback')"
                                        class="w-[233px]"
                                        @opened="getOptionData('feedbacks')"
                                        @scrolled="getNextPageOptionData('feedbacks')"
                                    />
                                    <MultiSelectFilter
                                        label="Duplicate"
                                        labelClass="label__class"
                                        :options="filterOptions.duplicates.data"
                                        v-model="filters.duplicates"
                                        :placeholder="$t('Select duplicate')"
                                        class="w-[233px]"
                                        @opened="getOptionData('duplicates')"
                                        @scrolled="getNextPageOptionData('duplicates')"
                                    />
                                    <MultiSelectFilter
                                        label="Control status (Appointment)"
                                        labelClass="label__class"
                                        :options="filterOptions.control_status_appointment.data"
                                        v-model="filters.control_status_appointment"
                                        :placeholder="$t('Select control status (appointment)')"
                                        class="w-[233px]"
                                        @opened="getOptionData('control_status_appointment')"
                                        @scrolled="getNextPageOptionData('control_status_appointment')"
                                        placeholderClass="overflow-hidden whitespace-nowrap text-ellipsis"
                                    />
                                    <MultiSelectFilter
                                        label="Contact record status"
                                        labelClass="label__class"
                                        :options="filterOptions.contact_record_status.data"
                                        v-model="filters.contact_record_status"
                                        :placeholder="$t('Select status')"
                                        class="w-[233px]"
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
       <div class="overview__tabs">
            <router-link v-if="hasPermission('contact-data-record:lead-view')" :to="{name: 'contact-data-records-leads-index'}" class="overview__tabs--item">{{ $t('Leads') }}</router-link>
            <div v-if="hasPermission('contact-data-record:termin-view')" class="overview__tabs--item active">{{ $t('Termine') }}</div>
            <router-link v-if="hasPermission('contact-data-record:all-view')" :to="{name: 'contact-data-records-all-index'}" class="overview__tabs--item">{{ $t('All') }}</router-link>
       </div>
       <div class="overview__body mt-0" style="height: initial;">
       <!-- <div class="overview__body mt-0"> -->
            <overview-table :paginationData="paginationData" :headerTop="hasAnyPermissions(['contact-data-record:allocation', 'contact-data-record:lead-again', 'contact-data-record:export'])">
                <template #header-top>
                    <div class="w-full" >
                        <CheckboxInput
                            v-model="isAllSelected"
                            :checkLabel="isAllSelected ? 'availability_deselect_all' : 'availability_select_all'"
                        />
                    </div>
                    <div class="flex gap-3.5">
                        <AllocateButton class="w-max" :disabled="!enableAllocateButton" @click="showAllocateModal"  v-if="hasPermission('contact-data-record:allocation')">
                            {{ $t("Allocate Appointment_s") }}
                        </AllocateButton>
                        <LeadAgainButton class="w-max" :disabled="!enableSendLeadAgainButton" @click.prevent="showLeadAgainModal" v-if="hasPermission('contact-data-record:lead-again')">
                            {{ $t("Set as Lead again") }}
                        </LeadAgainButton>
                        <LeadAgainButton class="w-max" :disabled="!enableSendLeadAgainButton" @click.prevent="showAppointmentLeadModal" v-if="hasPermission('contact-data-record:edit')">
                            {{ $t("Appointment Lead") }}
                        </LeadAgainButton>
                        <ExportButton class="w-max" tab="termin" v-if="hasPermission('contact-data-record:export')">
                            {{ $t("Export") }}
                        </ExportButton>
                    </div>
                </template>
                <template #header>
                    <div class="w-min flex">
                        <div style="width: 50px;"></div>
                        <SortColumn label="Appointment ID" columnName="appointment_id" style="width:110px;" />
                        <SortColumn label="Appointment Date" columnName="appointment_date" style="width:190px;" />
                        <SortColumn label="Appointment Time" columnName="appointment_time" style="width:190px;" />
                        <SortColumn label="Allocated to" columnName="allocated_to" style="width:160px;" :enable="false" />
                        <SortColumn label="VariableA" columnName="variable_a" style="width:160px;" :enable="false" />
                        <SortColumn label="Campaign" columnName="campaign_id" style="width:170px;" :enable="false" />
                        <SortColumn label="Correspondence Language" columnName="correspondence_language" style="width:225px;"/>
                        <SortColumn label="Canton" columnName="canton" style="width:160px;"/>
                        <SortColumn label="First Name" columnName="first_name" style="width:160px;"/>
                        <SortColumn label="Last Name" columnName="last_name" style="width:160px;"/>


                        <SortColumn label="Zip Code PLZ" columnName="zip_code" style="width:150px;"/>
                        <SortColumn label="City" columnName="city" style="width:200px;"/>
                        <SortColumn label="Number of persons in household" columnName="number_of_persons_in_household" style="width:280px;"/>
                        <SortColumn label="Control status (Appointment)" columnName="control_status_appointment" style="width:270px;"/>
                        <SortColumn label="Notes Control (Appointment)" columnName="notes_control_appointment" style="width:300px;"/>
                        <SortColumn label="Contact record status" columnName="contact_record_status" style="width:270px;"/>
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
    <ExportModal></ExportModal>

</template>
<script setup>
    import AddNewButton from '@/components/button/AddNewButton.vue'
    import Filters from '../components/Filters.vue'
    import Search from '@/components/form/Search.vue'
    import SortColumn from '@/components/sort/SortTableColumn.vue'
    import OverviewTable from './../components/OverviewTable.vue'

    import TableRow from './components/TableRow.vue'
    import MultiSelect from '@/components/form/MultiSelect.vue'
    import MultiSelectFilter from '@/components/form/MultiSelectFilter.vue'
    import DateRangeSelector from '@/components/form/DateRangeSelector.vue'
    import YearRangeSelector from '@/components/form/YearRangeSelector.vue'
    import TimeRangeSelector from '@/components/form/TimeRangeSelector.vue'
    import ButtonGradient from '@/components/button/Gradient.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';

    import {reactive, watch, ref, computed, inject, toRaw} from 'vue'
    import {useRoute, useRouter} from 'vue-router'
    import axios from 'axios'
    import AllocateButton from '../components/allocations/AllocateButton.vue';
    import AllocationModal from '../components/allocations/AllocationModal.vue';
    import CancelAllocationModal from '../components/allocations/CancelAllocationModal.vue';
    import ConfirmAllocationModal from '../components/allocations/ConfirmAllocationModal.vue';

    import ExportButton from '../components/exports/ExportButton.vue';
    import ExportModal from '../components/exports/ExportModal.vue'

    import LeadAgainButton from '../components/leadAgains/LeadAgainButton.vue'
    import LeadAgainModal from '../components/leadAgains/LeadAgainModal.vue'
    import AppointmentLeadModal from '../components/leadAgains/AppointmentLeadModal.vue'
    import ImportButton from '../components/imports/ImportButton.vue'


    const $vfm = inject("$vfm");

    const vueRoute = useRoute();
    const router = useRouter();

    const overviewData = ref([]);
    const paginationData = ref({});

    const filterOptions = reactive({
        internal_users: {
            data: []
        },
        sources: {
            data: []
        },
        status:  {
            data: []
        },
        variableA:  {
            data:[]
        },
        year: {
            start: null,
            end: null
        },
        cantons:  {
            data:[]
        },
        regions:  {
            data:[]
        },
        correspondence_languages:  {
            data:[]
        },
        other_languages:  {
            data:[]
        },
        campaigns:  {
            data:[]
        },
        saves:  {
            data:[]
        },
        health_insurances:  {
            data:[]
        },
        third_pillers:  {
            data:[]
        },
        contact_desireds:  {
            data:[]
        },
        leads:  {
            data:[]
        },
        feedbacks:  {
            data:[]
        },
        control_status_appointment:  {
            data:[]
        },
        duplicates:  {
            data:[]
        },
        contact_record_status:  {
            data:[]
        },
        // dates: {
        //     start: null,
        //     end: null
        // },
        // termin_dates: {
        //     start: null,
        //     end: null
        // },
        // allocation_dates: {
        //     start: null,
        //     end: null
        // },
    })

    const filters = reactive({
        internal_users: [],
        sources: [],
        variableA: [],
        year: null,
        cantons: [],
        regions: [],
        correspondence_languages: [],
        other_languages: [],
        campaigns: [],
        saves: [],
        health_insurances: [],
        third_pillers: [],
        contact_desireds: [],
        leads: [],
        feedbacks: [],
        control_status_appointment: [],
        duplicates: [],
        contact_record_status: [],
        start_date: null,
        end_date: null,
        termin_start_date: null,
        termin_end_date: null,
        termin_start_time: null,
        termin_end_time: null,


        allocation_start_date: null,
        allocation_end_date: null,
        year_start: null,
        year_end: null,

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
        if(filters.termin_start_date && filters.termin_end_date){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }
        if(filters.termin_start_time && filters.termin_end_time){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
        }

        if(filters.allocation_start_date && filters.allocation_end_date){
            filtersValues['allocation_start_date'] = filters.allocation_start_date;
            filtersValues['allocation_end_date'] = filters.allocation_end_date;
        }

        if(filters.year_start && filters.year_end){
            filtersValues['year_start'] = filters.year_start;
            filtersValues['year_end'] = filters.year_end;
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

        filters['termin_start_date'] = null;
        filters['termin_end_date'] = null;

        filters['termin_start_time'] = null;
        filters['termin_end_time'] = null;

        filters['allocation_start_date'] = null;
        filters['allocation_end_date'] = null;

        filters['year_start'] = null;
        filters['year_end'] = null;

        router.push({query: {...filtersValues}})
    }



    const getQueryParamFromUrl =() => {
        const queries = vueRoute.query;

        Object.keys(filters).forEach(function(key, index) {
            if(filters[key] && typeof filters[key] == 'object' && queries[key] ){
                filters[key] = queries[key].toString().split(',')
            }
        });

        filters['start_date'] = queries['start_date']?? null;
        filters['end_date'] = queries['end_date']?? null;

        filters['termin_start_date'] = queries['termin_start_date']?? null;
        filters['termin_end_date'] = queries['termin_end_date']?? null;

        filters['termin_start_time'] = queries['termin_start_time']?? null;
        filters['termin_end_time'] = queries['termin_end_time']?? null;

        filters['allocation_start_date'] = queries['allocation_start_date']?? null;
        filters['allocation_end_date'] = queries['allocation_end_date']?? null;

        filters['year_start'] = queries['year_start']?? null;
        filters['year_end'] = queries['year_end']?? null;
        // filters['search'] = queries['search']?? null;
    }


    const getData = async ()=>{
        const {data} = await axios.get(route('contact-data-records.termin.index', {_query: vueRoute.query}))
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

        if(filters.start_date && filters.end_date && filtersValues.start_date && filtersValues.end_date ){
            filtersValues['start_date'] = filters.start_date;
            filtersValues['end_date'] = filters.end_date;
        }

        if(filters.termin_start_date && filters.termin_end_date  && filtersValues.termin_start_date && filtersValues.termin_end_date ){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }

        if(filters.termin_start_time && filters.termin_end_time  && filtersValues.termin_start_time && filtersValues.termin_end_time ){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
        }

        if(filters.allocation_start_date && filters.allocation_end_date && filtersValues.allocation_start_date && filtersValues.allocation_end_date ){
            filtersValues['allocation_start_date'] = filters.allocation_start_date;
            filtersValues['allocation_end_date'] = filters.allocation_end_date;
        }
        if(filters.year_start && filters.year_end && filtersValues.year_start && filtersValues.year_end ){
            filtersValues['year_start'] = filters.year_start;
            filtersValues['year_end'] = filters.year_end;
        }

        filtersValues['column'] = column

        const {data} = await axios.get(route('contact-data-records.termin.get-filters', {_query:filtersValues}))

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

        if(filters.termin_start_date && filters.termin_end_date && filtersValues.termin_start_date && filtersValues.termin_end_date ){
            filtersValues['termin_start_date'] = filters.termin_start_date;
            filtersValues['termin_end_date'] = filters.termin_end_date;
        }

        if(filters.termin_start_time && filters.termin_end_time && filtersValues.termin_start_time && filtersValues.termin_end_time ){
            filtersValues['termin_start_time'] = filters.termin_start_time;
            filtersValues['termin_end_time'] = filters.termin_end_time;
        }

        if(filters.allocation_start_date && filters.allocation_end_date && filtersValues.allocation_start_date && filtersValues.allocation_end_date ){
            filtersValues['allocation_start_date'] = filters.allocation_start_date;
            filtersValues['allocation_end_date'] = filters.allocation_end_date;
        }

        if(filters.year_start && filters.year_end && filtersValues.year_start && filtersValues.year_end ){
            filtersValues['year_start'] = filters.year_start;
            filtersValues['year_end'] = filters.year_end;
        }

        filtersValues['column'] = column
        filtersValues['page'] =  filterOptions[column]['current_page'] ? filterOptions[column]['current_page'] + 1 : 1;
        filtersValues['except_filter'] = column;

        const {data} = await axios.get(route('contact-data-records.termin.get-filters', {_query:filtersValues}))

        filterOptions[column]['data'] = [...filterOptions[column]['data'], ...data.data]
        filterOptions[column]['current_page'] =  filtersValues['page']
    }


    watch(
        () => vueRoute.fullPath,
        async (newValue, oldValue) => {
            if(vueRoute.name == 'contact-data-records-termin-index') {
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
        // return Object.values(selectedRows.value).some((item)=>item);
        return true;
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

    const enableSendLeadAgainButton = computed(()=> {
        return Object.values(selectedRows.value).some((item)=>item);
    })

    const showLeadAgainModal = ()=> {
        const options = {
            component: LeadAgainModal,
            bind: {
                contact_data_records: selectedRows.value,
                type: 'lead',
                title: "Set as Lead again?",
                description: "If you set a contact record to “New lead”, it will be placed at the start of the workflow (New status). In addition, the data should then be checked and updated if necessary, and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to set the selected contact data records to “Lead again”?",
            },
        }
        $vfm.show(options)
    }

    const showAppointmentLeadModal = ()=> {
        const options = {
            component: AppointmentLeadModal,
            bind: {
                contact_data_records: selectedRows.value,
                title: "Set Appointment Lead?",
                description: "If you set a contact record to “Appointment Lead”, it is placed at the start of the workflow (New status) and receives the category “Appointment Lead”. In addition, the data should then be checked and updated if necessary and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to set the selected contact records to “Appointment Lead”",
            },
        }
        $vfm.show(options)
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

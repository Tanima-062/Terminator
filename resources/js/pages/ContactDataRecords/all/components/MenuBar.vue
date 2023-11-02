<template>
  <div class="table__menubar justify-start" v-click-away="() => (openDropdownMenu = false)">
    <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
      <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363" />
    </svg>
    <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
      <li class="flex whitespace-nowrap h-[25px] items-center px-[14px] cursor-pointer" @click="showLeadAgainModal" v-if="hasPermission('contact-data-record:lead-again')">
        {{ $t('Set as Lead again') }}
      </li>
      <li class="flex whitespace-nowrap h-[25px] items-center px-[14px]">
        <router-link class="!px-0" :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id } }">{{ $t('View Details') }}</router-link>
      </li>
      <!-- Check duplicate will implament later -->
      <!-- <li class="flex whitespace-nowrap h-[25px] items-center px-[14px]" v-if="status == 'Check Duplicate' || status == 'Duplicate'">
        <router-link class="!px-0" :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id }, query: { duplicate_check: true } }">{{ $t('Check Duplicate') }}</router-link>
      </li> -->
      <li>
        <router-link :to="{ name: 'contact-data-records-edit', params: { id: contact_data_record.id } }">{{ $t('Edit') }}</router-link>
    </li>
      <li v-if="status == 'Inactive'">
        <a @click.prevent="changeStatus('New')"> {{ $t('Activate') }}</a>
      </li>
      <li v-if="status != 'Inactive'">
        <a @click.prevent="changeStatus('Inactive')">{{ $t('Deactivate') }}</a>
      </li>


      <li v-if="contact_data_record.category == 'lead' && contact_data_record.contact_record_status != 'Duplicate' && contact_data_record.contact_record_status != 'Check Duplicate' && hasPermission('contact-data-record:allocation')">
        <a @click.prevent="allocate">{{ $t('Allocate Contact Data Record') }}</a>
      </li>

    </ul>
  </div>
</template>

<script>
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
import AllocationModal from '../../components/allocations/AllocationModal.vue';
import LeadAgainModal from '../../components/leadAgains/LeadAgainModal.vue'

export default {
  props: {
    contact_data_record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      status: this.contact_data_record.contact_record_status,
      openDropdownMenu: false,
    }
  },
  methods: {
    changeStatus(status) {
      const title = status == 'New' ? 'Activate Contact Data Record?' : 'Deactivate Contact Data Record?'
      const description =
        status == 'New'
          ? trans(
              'If you activate an inactive contact record, it will be placed at the start of the workflow (New status). In addition, the data should then be checked and updated if necessary, and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to activate the contact record with the ID “:prefix_id“?',
              { prefix_id: this.contact_data_record.prefix_id }
            )
          : trans('Are you sure you really want to deactivate the contact data record with the ID “:prefix_id”?', { prefix_id: this.contact_data_record.prefix_id })

      this.$vfm.show('confirmation', {
        title,
        description,
        yesClick: () => this.updateStatus(status),
      })
    },
    async updateStatus(status) {
      const res = await axios.put(route('contact-data-records.update-status', { contact_data_record: this.contact_data_record.id }), { status })

      const description = status == 'New' ? 'The contact data record with the ID “:prefix_id” was successfully activated' : 'The contact data record with the ID “:prefix_id” was successfully deactivated'
      this.status = status

      this.$vfm.show('success-notification', {
        description: trans(description, { prefix_id: this.contact_data_record.prefix_id }),
        // duration: 10000
      })
      this.emitter.emit(`update-status-${this.contact_data_record.id}`, status)
    },

    assignAppointment() {
      this.$vfm.show({
        component: AssignAppointmentModal,

        bind: {
          contact_data_record_id: this.contact_data_record.id,
        },

        on: {
          created: () =>
            this.$vfm.show('success-notification', {
              description: trans('The appointment was created successfully.'),
            }),
        },
      })
    },
    allocate(){
        const rows = {
            [this.contact_data_record.id]: true
        }
        const options = {
            component: AllocationModal,
            bind: {
                contact_data_records: rows,
                type: 'all'
            },
        }
        this.$vfm.show(options)
    },
    showLeadAgainModal(){
        const options = {
            component: LeadAgainModal,
            bind: {
                contact_data_records: {
                    [this.contact_data_record.id]: true
                },
                type: 'lead',
                title: "Set as Lead again?",
                description: "If you set a contact record to “New lead”, it will be placed at the start of the workflow (New status). In addition, the data should then be checked and updated if necessary, and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to set the selected contact data records to “Lead again”?",
            },
        }
        this.$vfm.show(options)
    }
  },
}
</script>

<style lang="scss" scoped>

.table__menubar__dropdown {
    width: max-content;
}
</style>

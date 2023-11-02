<template>
  <div class="table__menubar justify-start" v-click-away="() => (openDropdownMenu = false)">
    <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
      <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363" />
    </svg>
    <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
      <li class="flex whitespace-nowrap h-[25px] items-center px-[14px]">
        <router-link class="!px-0" :to="{ name: 'termins-show', params: { id: contact_data_record.id } }">{{ $t('View Details') }}</router-link>
      </li>
      <li>
        <router-link :to="{ name: 'termins-edit', params: { id: contact_data_record.id } }" v-if="hasPermission('contact-data-record:edit')">{{ $t('Edit') }}</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
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

  },
}
</script>

<style lang="scss" scoped>
.table__menubar__dropdown {
    width: max-content;
}
</style>

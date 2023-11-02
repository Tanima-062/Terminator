<template>
  <div class="content bg-white rounded-[15px] shadow-container mx-6 px-[43px] pt-[42px] pb-[62px] min-h-[89vh]">
    <Back class="mb-[31px]" />
    <Tabs />
    <div class="contents-wrapper">
      <div class="headers flex gap-x-4 mb-6 justify-start">
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[15%]">{{ $t('Time') }}</h4>
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[10%]">{{ $t('User') }}</h4>
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[20%]">{{ $t('Action') }}</h4>
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[20%]">{{ $t('Notes') }}</h4>
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[17%]">{{ $t('Status change') }}</h4>
        <h4 class="text-[18x] leading-[22px] font-semibold text-input w-[18%]">{{ $t('Category change') }}</h4>
      </div>

      <div class="content flex justify-start gap-x-4 mb-5 text-[#707070]" v-for="history in histories" :class="{ 'text-[#9D5793]': history.new_status == 'Check Duplicate' }">
        <div class="wrapper flex flex-col gap-6 w-[15%]">
          <p class="text-16" v-date-format="{ date: history.created_at, format: 'DD.MM.YYYY &nbsp HH:mm' }"></p>
        </div>

        <div class="wrapper flex flex-col gap-6 w-[10%]">
          <p class="text-16">{{history.user.full_name }}</p>
        </div>

        <div class="wrapper flex flex-col gap-6 w-[20%]">
          <p class="text-16">{{ $t(history.action) }}</p>
        </div>

        <div class="wrapper flex flex-col gap-6 w-[20%]">
          <p class="text-16">{{ history.notes }}</p>
        </div>

        <div class="wrapper flex flex-col gap-6 w-[17%]">
          <p class="text-16">{{ history.status_change ? `${$t(history.old_status ?? 'No One')} - ${$t(history.new_status ?? 'No One')}` : $t('No One') }}</p>
        </div>

        <div class="wrapper flex flex-col gap-6 w-[18%]">
          <p class="text-16">{{ history.category_change ? `${$t(history.old_category ?? 'No One')} - ${$t(history.new_category ?? 'No One')}` : $t('No One') }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Tabs from '../components/Tabs.vue'
import Back from '@/components/form/Back.vue'
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const vRoute = useRoute()
const vRouter = useRouter()
const histories = ref()

const getHistories = async (id) => {
  try {
    const { data } = await axios.get(route('contact-data-records.history', { contact_data_record: id }))
    histories.value = data
  } catch (error) {
    if(error.response.status == 404) {
        vRouter.push({name: '404'})
    }
  }
}
const resolveLink = (user) => {
  switch (user.type) {
    case 'company_admin':
      return vRouter.resolve({ name: 'customer-company-admin-show', params: { id: user.customer_company_admin.id } }).href
    case 'internal_user':
      return vRouter.resolve({ name: 'customer-company-admin-show', params: { id: user.internal_user.id } }).href
    default:
      return '/'
  }
}

onMounted(async () => {
  const contact_data_record_id = vRoute.params.id
  await getHistories(contact_data_record_id)
})
</script>

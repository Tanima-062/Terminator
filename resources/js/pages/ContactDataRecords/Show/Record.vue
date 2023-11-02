<template>
  <div class="content bg-white rounded-[15px] shadow-container mx-6 px-[43px] pt-[42px] pb-[62px] min-h-[89vh]">
    <Back class="mb-[31px]" />
    <Tabs />
    <div class="contents-wrapper flex gap-[34px] flex-col">
      <template v-if="contact_data_record">
        <DuplicateCheckComponent
          :duplicates="contact_data_record.duplicates"
          v-if="$route.query.duplicate_check && contact_data_record.duplicates.length"
          :contact_data_record_id="contact_data_record.id"
          @updateContactDataRecord="getContactRecordData(vRoute.params.id)"
        />

        <DetailsContainer title="General contact record information" :contents="contact_data_record.general_information" :translations_required="['Category', 'Source']"></DetailsContainer>

        <DetailsContainer title="Person Information" :contents="contact_data_record.person_information" :translations_required="['Salutation']">
          <template #content="{ key, value, translations_required }">
            <template v-if="key == 'Correspondence Language'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ useLanguageCodeToName(value) }}</p>
            </template>

            <template v-else-if="key == 'Other Languages'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ value?.map((code) => useLanguageCodeToName(code)).join(', ') }}</p>
            </template>

            <template v-else>
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ translations_required.includes(key) ? $t(value.toString() ?? '') : value }}</p>
            </template>
          </template>
        </DetailsContainer>

        <DetailsContainer title="Address" :contents="contact_data_record.address" :translations_required="['Canton', 'Region']">
          <template #content="{ key, value, translations_required }">
            <template v-if="key == 'Country'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ useGetCountryName(value) }}</p>
            </template>

            <template v-else>
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ translations_required.includes(key) ? $t(value.toString() ?? '') : value }}</p>
            </template>
          </template>
        </DetailsContainer>

        <DetailsContainer
          title="Appointment relevant Information"
          :contents="contact_data_record.appointment_relevant_information"
          :translations_required="[
            'Car Insurance',
            '3rd Pillar',
            'Household Goods',
            'Legal Protection',
            'Health Status',
            'Work Activity',
            'Number of persons in household',
            'Saving',
            'Satisfaction',
            'Last Health Insurance Change',
            'Contact person for insurance questions',
            'Health Insurance',
          ]"
        >
        </DetailsContainer>

        <DetailsContainer title="Contact information" :contents="contact_data_record.contact_information">
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Availability') }}</h4>
            <div class="flex gap-[10px] items-center">
              <Checkbox :checked="contact_data_record.contact_information['Availability']" width="17" height="17" /> <span class="text-[#636363] text-16 leading-[19px] font-roboto">{{ $t('No information') }}</span>
            </div>
          </div>
              <Availabilities v-if="!contact_data_record.contact_information['Availability']" :availability="contact_data_record.availability" class="col-span-3" />

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Desired contact') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.contact_information['Desired contact']) }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Desired consulting channel') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.contact_information['Desired consulting channel']) }}</p>
          </div>
        </DetailsContainer>

        <Accordion title="Dates" bodyClass="flex flex-col gap-y-6 !px-[30px]">
          <AppointmentTable :contact_data_record="contact_data_record" v-model="appointments" :hide_create_btn="true" />
        </Accordion>

        <DetailsContainer title="Costs, Revenues and Profit" :contents="contact_data_record.costs_revenues_and_profit">
          <template #content="{ key, value }">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
            <p class="text-16 text-[#707070]"><span class="font-medium text-input">CHF</span> {{ value }}</p>
          </template>
        </DetailsContainer>
      </template>

      <ButtonGradient class="w-[187px] mt-[66px] flex items-center justify-center" type="submit" @click="$router.push({ name: 'contact-data-records-edit', id: $route.params.id })">
        <PencilIcon />
        <span class="m-auto">{{ $t('Edit') }}</span>
      </ButtonGradient>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref, computed } from 'vue'
import PencilIcon from '@/components/icons/PencilIcon.vue'
import DetailsContainer from '../components/DetailsContainer.vue'
import ButtonGradient from '@/components/button/Gradient.vue'
import Tabs from '../components/Tabs.vue'
import Back from '@/components/form/Back.vue'
import { useLanguageCodeToName, useGetCountryName } from '@/composables/translation.js'
import Checkbox from '../components/Checkbox.vue'
import axios from 'axios'
import DuplicateCheckComponent from '../components/DuplicateCheckComponent.vue'
import Accordion from '@/components/utils/Accordion.vue'
import AppointmentTable from '../components/AppointmentTable.vue'
import Availabilities from '../components/Availabilities.vue'

const vRoute = useRoute()
const vRouter = useRouter()
const contact_data_record = ref()

const appointments = computed(() => {
  return contact_data_record.value.dates ?? []
})

const getContactRecordData = async (id) => {
  try {
    const { data } = await axios.get(route('contact-data-records.show', { contact_data_record: id }))
    contact_data_record.value = data
  } catch (error) {
    // console.log(error)
    if (error.response.status == 404) {
      vRouter.push({ name: '404' })
    }
  }
}

onMounted(async () => {
  await getContactRecordData(vRoute.params.id)
})
</script>

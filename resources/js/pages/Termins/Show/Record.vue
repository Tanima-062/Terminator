<template>
  <div class="content bg-white rounded-[15px] shadow-container mx-6 px-[43px] pt-[42px] pb-[62px] min-h-[89vh]">
    <Back class="mb-[31px]" />
    <Tabs />
    <div class="contents-wrapper flex gap-[34px] flex-col">
      <template v-if="contact_data_record">
        <!-- <DuplicateCheckComponent
          :duplicates="contact_data_record.duplicates"
          v-if="$route.query.duplicate_check && contact_data_record.duplicates.length"
          :contact_data_record_id="contact_data_record.id"
          @updateContactDataRecord="getContactRecordData(vRoute.params.id)"
        /> -->

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

        <DetailsContainer title="Contact information" :contents="contact_data_record.contact_information" :translations_required="['Desired contact', 'Desired consulting channel']">
          <template #content="{ key, value, translations_required }">
            <template v-if="key == 'Availability'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <div class="flex gap-[10px] items-center">
                <Checkbox :checked="!value" width="17" height="17" /> <span class="text-[#636363] text-16 leading-[19px] font-roboto">{{ $t('No information') }}</span>
              </div>
            </template>

            <template v-else>
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t(key) }}</h4>
              <p class="text-16 text-[#707070]">{{ translations_required.includes(key) ? $t(value ?? '') : value }}</p>
            </template>
          </template>
        </DetailsContainer>


        <Accordion title="Dates" bodyClass="flex flex-col gap-y-6 !px-[30px]">
        <div class="content-container grid grid-cols-3 gap-y-11 ">

          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment ID') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.last_appointment?.prefix_id }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment Date') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.last_appointment? formateDate(contact_data_record.last_appointment.appointment_date) : '' }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment Time') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.last_appointment? formateTime(contact_data_record.last_appointment.appointment_time) : '' }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment took place?') }}</h4>
              <div class="flex">
                    <p class="text-16 text-[#707070] flex gap-1 items-center mr-10">
                        <RadioInput :active="contact_data_record?.intermediary_feedback?.appointment_took_place" /> {{ $t('Yes') }}
                    </p>
                    <p class="text-16 text-[#707070] flex gap-1 items-center">
                        <RadioInput :active="!contact_data_record?.intermediary_feedback?.appointment_took_place" /> {{ $t('No') }}
                    </p>
                </div>
            <!-- {{ contact_data_record?.intermediary_feedback.appointment_took_place ? 'Yes': 'No' }} -->
              <!-- <RadioInput label="Device Authentication required?" labelClass="whitespace-nowrap" v-model="form.device_authentication_required" :options="yes_no_options" :asterisk="true" /> -->

          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Intermediary remarks') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record?.intermediary_feedback?.intermediary_remarks }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Reason') }}</h4>
              <!-- {{ contact_data_record.intermediary_feedback }} -->
              <!-- <p class="text-16 text-[#707070]">{{ contact_data_record.intermediary_feedback ? $t(contact_data_record.intermediary_feedback.reason): '' }}</p> -->
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Photo on site') }}</h4>
              <p class="text-16 text-[#707070]">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.5" y="0.5" width="79" height="79" rx="5.5" stroke="#A4A4A4" stroke-dasharray="3 3"/>
                    <path d="M40 24.001V35.0844" stroke="#A4A4A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34 29.54C39.0768 29.54 41.9232 29.54 47 29.54" stroke="#A4A4A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M28.1307 52H27.1989L29.2926 46.1818H30.3068L32.4006 52H31.4688L29.8239 47.2386H29.7784L28.1307 52ZM28.2869 49.7216H31.3097V50.4602H28.2869V49.7216ZM34.022 49.4091V52H33.1726V47.6364H33.9879V48.3466H34.0419C34.1423 48.1155 34.2995 47.9299 34.5135 47.7898C34.7294 47.6496 35.0012 47.5795 35.3288 47.5795C35.6262 47.5795 35.8866 47.642 36.1101 47.767C36.3336 47.8902 36.5069 48.0739 36.63 48.3182C36.7531 48.5625 36.8146 48.8646 36.8146 49.2244V52H35.9652V49.3267C35.9652 49.0104 35.8828 48.7633 35.718 48.5852C35.5533 48.4053 35.3269 48.3153 35.0391 48.3153C34.8421 48.3153 34.6669 48.358 34.5135 48.4432C34.362 48.5284 34.2417 48.6534 34.1527 48.8182C34.0656 48.9811 34.022 49.178 34.022 49.4091ZM38.8033 46.1818V52H37.9538V46.1818H38.8033ZM41.2159 52.0966C40.9394 52.0966 40.6894 52.0455 40.4659 51.9432C40.2424 51.839 40.0653 51.6884 39.9347 51.4915C39.8059 51.2945 39.7415 51.053 39.7415 50.767C39.7415 50.5208 39.7888 50.3182 39.8835 50.1591C39.9782 50 40.1061 49.8741 40.267 49.7812C40.428 49.6884 40.608 49.6184 40.8068 49.571C41.0057 49.5237 41.2083 49.4877 41.4148 49.4631C41.6761 49.4328 41.8883 49.4081 42.0511 49.3892C42.214 49.3684 42.3324 49.3352 42.4062 49.2898C42.4801 49.2443 42.517 49.1705 42.517 49.0682V49.0483C42.517 48.8002 42.447 48.608 42.3068 48.4716C42.1686 48.3352 41.9621 48.267 41.6875 48.267C41.4015 48.267 41.1761 48.3305 41.0114 48.4574C40.8485 48.5824 40.7358 48.7216 40.6733 48.875L39.875 48.6932C39.9697 48.428 40.108 48.214 40.2898 48.0511C40.4735 47.8864 40.6847 47.767 40.9233 47.6932C41.1619 47.6174 41.4129 47.5795 41.6761 47.5795C41.8504 47.5795 42.035 47.6004 42.2301 47.642C42.4271 47.6818 42.6108 47.7557 42.7812 47.8636C42.9536 47.9716 43.0947 48.1259 43.2045 48.3267C43.3144 48.5256 43.3693 48.7841 43.3693 49.1023V52H42.5398V51.4034H42.5057C42.4508 51.5133 42.3684 51.6212 42.2585 51.7273C42.1487 51.8333 42.0076 51.9214 41.8352 51.9915C41.6629 52.0616 41.4564 52.0966 41.2159 52.0966ZM41.4006 51.4148C41.6354 51.4148 41.8362 51.3684 42.0028 51.2756C42.1714 51.1828 42.2992 51.0616 42.3864 50.9119C42.4754 50.7604 42.5199 50.5985 42.5199 50.4261V49.8636C42.4896 49.8939 42.4309 49.9223 42.3438 49.9489C42.2585 49.9735 42.161 49.9953 42.0511 50.0142C41.9413 50.0312 41.8343 50.0473 41.7301 50.0625C41.6259 50.0758 41.5388 50.0871 41.4688 50.0966C41.304 50.1174 41.1534 50.1525 41.017 50.2017C40.8826 50.2509 40.7746 50.322 40.6932 50.4148C40.6136 50.5057 40.5739 50.6269 40.5739 50.7784C40.5739 50.9886 40.6515 51.1477 40.8068 51.2557C40.9621 51.3617 41.16 51.4148 41.4006 51.4148ZM46.3303 53.7273C45.9837 53.7273 45.6854 53.6818 45.4354 53.5909C45.1873 53.5 44.9846 53.3797 44.8274 53.2301C44.6702 53.0805 44.5528 52.9167 44.4751 52.7386L45.2053 52.4375C45.2564 52.5208 45.3246 52.6089 45.4098 52.7017C45.4969 52.7964 45.6143 52.8769 45.7621 52.9432C45.9117 53.0095 46.1039 53.0426 46.3388 53.0426C46.6607 53.0426 46.9268 52.964 47.1371 52.8068C47.3473 52.6515 47.4524 52.4034 47.4524 52.0625V51.2045H47.3984C47.3473 51.2973 47.2734 51.4006 47.1768 51.5142C47.0821 51.6278 46.9515 51.7263 46.7848 51.8097C46.6181 51.893 46.4013 51.9347 46.1342 51.9347C45.7895 51.9347 45.4789 51.8542 45.2024 51.6932C44.9278 51.5303 44.71 51.2907 44.549 50.9744C44.3899 50.6562 44.3104 50.2652 44.3104 49.8011C44.3104 49.3371 44.389 48.9394 44.5462 48.608C44.7053 48.2765 44.9231 48.0227 45.1996 47.8466C45.4761 47.6686 45.7895 47.5795 46.1399 47.5795C46.4107 47.5795 46.6295 47.625 46.7962 47.7159C46.9628 47.8049 47.0926 47.9091 47.1854 48.0284C47.2801 48.1477 47.353 48.2528 47.4041 48.3438H47.4666V47.6364H48.299V52.0966C48.299 52.4716 48.2119 52.7794 48.0376 53.0199C47.8634 53.2604 47.6276 53.4384 47.3303 53.554C47.0348 53.6695 46.7015 53.7273 46.3303 53.7273ZM46.3217 51.2301C46.5661 51.2301 46.7725 51.1733 46.9411 51.0597C47.1115 50.9441 47.2403 50.7794 47.3274 50.5653C47.4164 50.3494 47.4609 50.0909 47.4609 49.7898C47.4609 49.4962 47.4174 49.2377 47.3303 49.0142C47.2431 48.7907 47.1153 48.6165 46.9467 48.4915C46.7782 48.3646 46.5698 48.3011 46.3217 48.3011C46.0661 48.3011 45.853 48.3674 45.6825 48.5C45.5121 48.6307 45.3833 48.8087 45.2962 49.0341C45.2109 49.2595 45.1683 49.5114 45.1683 49.7898C45.1683 50.0758 45.2119 50.3267 45.299 50.5426C45.3861 50.7585 45.5149 50.9271 45.6854 51.0483C45.8577 51.1695 46.0698 51.2301 46.3217 51.2301ZM51.3189 52.0881C50.889 52.0881 50.5187 51.9962 50.2081 51.8125C49.8994 51.6269 49.6607 51.3665 49.4922 51.0312C49.3255 50.6941 49.2422 50.2992 49.2422 49.8466C49.2422 49.3996 49.3255 49.0057 49.4922 48.6648C49.6607 48.3239 49.8956 48.0578 50.1967 47.8665C50.4998 47.6752 50.8539 47.5795 51.2592 47.5795C51.5054 47.5795 51.7441 47.6203 51.9751 47.7017C52.2062 47.7831 52.4136 47.911 52.5973 48.0852C52.781 48.2595 52.9259 48.4858 53.032 48.7642C53.138 49.0407 53.1911 49.3769 53.1911 49.7727V50.0739H49.7223V49.4375H52.3587C52.3587 49.214 52.3132 49.0161 52.2223 48.8438C52.1314 48.6695 52.0036 48.5322 51.8388 48.4318C51.6759 48.3314 51.4846 48.2812 51.2649 48.2812C51.0263 48.2812 50.8179 48.34 50.6399 48.4574C50.4638 48.5729 50.3274 48.7244 50.2308 48.9119C50.1361 49.0975 50.0888 49.2992 50.0888 49.517V50.0142C50.0888 50.3059 50.1399 50.554 50.2422 50.7585C50.3464 50.9631 50.4912 51.1193 50.6768 51.2273C50.8625 51.3333 51.0793 51.3864 51.3274 51.3864C51.4884 51.3864 51.6352 51.3636 51.7678 51.3182C51.9003 51.2708 52.0149 51.2008 52.1115 51.108C52.2081 51.0152 52.282 50.9006 52.3331 50.7642L53.1371 50.9091C53.0727 51.1458 52.9571 51.3532 52.7905 51.5312C52.6257 51.7074 52.4183 51.8447 52.1683 51.9432C51.9202 52.0398 51.6371 52.0881 51.3189 52.0881Z" fill="#636363"/>
                </svg>
              </p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Result') }}</h4>
              <!-- <p class="text-16 text-[#707070]">--------------------</p> -->
                <div class="flex">
                    <p class="text-16 text-[#707070] flex gap-1 items-center mr-5">
                        <RadioInput :active="contact_data_record?.intermediary_feedback?.outcome == 'Positive' " /> {{ $t('Positive') }}
                    </p>
                    <p class="text-16 text-[#707070] flex gap-1 items-center mr-5">
                        <RadioInput :active="contact_data_record?.intermediary_feedback?.outcome == 'Negative'" /> {{ $t('Negative') }}
                    </p>
                    <p class="text-16 text-[#707070] flex gap-1 items-center">
                        <RadioInput :active="contact_data_record?.intermediary_feedback?.outcome == 'Follow up contact necessary'" /> {{ $t('Follow up contact necessary') }}
                    </p>
                </div>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Contracts concluded') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record?.intermediary_feedback?.contracts_concluded }}</p>
          </div>
          <div class="content flex flex-col gap-3" v-if="contact_data_record?.intermediary_feedback?.outcome == 'Negative'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Reason') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.intermediary_feedback ? $t(contact_data_record.intermediary_feedback?.reason): '' }}</p>
          </div>
          <div class="content flex flex-col gap-3" v-if="contact_data_record?.intermediary_feedback?.reason == 'Other'">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Other') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record?.intermediary_feedback.other }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Expiry Year') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record?.intermediary_feedback?.expiry_year }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Follow up contact date') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.intermediary_feedback &&  contact_data_record.intermediary_feedback.follow_up_contact_date ? formateDate(contact_data_record?.intermediary_feedback?.follow_up_contact_date): '' }}</p>
          </div>
          <div class="content flex flex-col gap-3" >
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Follow up contact time') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.intermediary_feedback &&  contact_data_record.intermediary_feedback.follow_up_contact_time ? formateTime(contact_data_record?.intermediary_feedback?.follow_up_contact_time): '' }}</p>
          </div>
        </div>


        </Accordion>
      </template>

      <ButtonGradient class="w-[187px] mt-[66px] flex items-center justify-center" type="submit" @click="$router.push({ name: 'termins-edit', id: $route.params.id })">
        <PencilIcon />
        <span class="m-auto">{{ $t('Edit') }}</span>
      </ButtonGradient>
    </div>
  </div>
</template>

<script setup>
import { useRoute,useRouter } from 'vue-router'
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
import RadioInput from '@/components/icons/RadioInput.vue'


const vRoute = useRoute()
const vRouter = useRouter()
const contact_data_record = ref()



const appointments = computed(() => {
  return contact_data_record.value.dates ?? []
})

const getContactRecordData = async (id) => {
  try {
    const { data } = await axios.get(route('termin.show', { contact_data_record: id }))
    contact_data_record.value = data
  } catch (error) {
    if(error.response.status == 404) {
        vRouter.push({name: '404'})
    }
  }
}

onMounted(async () => {
  await getContactRecordData(vRoute.params.id)
})
</script>

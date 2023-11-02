<template>
  <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]">
    <h1 class="text-formHeading text-title font-poppins mb-[60px]">
      {{ $t('Add New Customer Company') }}
    </h1>

    <form class="form grid grid-cols-3 gap-x-[251px]" @submit.prevent="submit">
      <!-- First Row -->
      <div class="flex flex-col">
        <h2 class="label text-formLabel font-inter text-input mb-[10px]">{{ $t('Company ID') }}*</h2>
        <p class="text-value text-4 font-semibold font-inter leading-[19px]">
          {{ prefix_id }}
        </p>
      </div>

      <TextInput
        label="Company Name"
        :placeholder="'Enter Company Name'"
        :asterisk="true"
        maxLength="51"
        v-model="form.name"
        :error="v$.name.$errors[0]?.$message"
        @input="
          () => {
            v$.name.$touch()
          }
        "
      />

      <TextInput
        label="Company Alias Name"
        :placeholder="'Enter Company Alias Name'"
        :asterisk="true"
        maxLength="51"
        v-model="form.alias_name"
        :error="v$.alias_name.$errors[0]?.$message"
        @input="
          () => {
            v$.alias_name.$touch()
          }
        "
      />

      <!-- Second Row -->
      <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[34px]">
        {{ $t('Company Address') }}
      </h1>

      <TextInput
        label="Street Name"
        :placeholder="'Enter Street Name'"
        :asterisk="true"
        maxLength="31"
        v-model="form.street_name"
        :error="v$.street_name.$errors[0]?.$message"
        @input="
          () => {
            v$.street_name.$touch()
          }
        "
      />

      <TextInput
        label="Street Number"
        :placeholder="'Enter Street Number'"
        :asterisk="true"
        maxLength="31"
        v-model="form.street_number"
        :error="v$.street_number.$errors[0]?.$message"
        @input="
          () => {
            v$.street_number.$touch()
          }
        "
      />

      <TextInput
        label="Zip Code"
        :placeholder="'Enter Zip Code'"
        :asterisk="true"
        v-model="form.zip_code"
        :maxLength="5"
        :error="v$.zip_code.$errors.length ? (v$.zip_code.maxLength.$invalid ? $t('Maximum :length characters possible', { length: 4 }) : true) : false"
        @input="v$.zip_code.$touch()"
      />
      <!-- Third Row -->
      <div class="spacer mt-[35px] col-span-3"></div>

      <TextInput
        label="City"
        :placeholder="'Enter City'"
        :asterisk="true"
        maxLength="31"
        v-model="form.city"
        :error="v$.city.$errors[0]?.$message"
        @input="
          () => {
            v$.city.$touch()
          }
        "
      />

      <CountrySelect v-model="country_iso_code" label="Country" :asterisk="true" placeholder="Select Country" :hide_search_input="true" :error="v$.country_iso_code.$errors.length > 0" />

      <!-- Fourth Row -->
      <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[34px]">
        {{ $t('Contact Person') }}
      </h1>

      <TextInput
        label="First Name"
        :placeholder="'Enter First Name'"
        :asterisk="true"
        maxLength="31"
        v-model="form.contact_person_first_name"
        :error="v$.contact_person_first_name.$errors[0]?.$message"
        @input="
          () => {
            v$.contact_person_first_name.$touch()
          }
        "
      />

      <TextInput
        label="Last Name"
        :placeholder="'Enter Last Name'"
        :asterisk="true"
        maxLength="31"
        v-model="form.contact_person_last_name"
        :error="v$.contact_person_last_name.$errors[0]?.$message"
        @input="
          () => {
            v$.contact_person_last_name.$touch()
          }
        "
      />

      <div class="spacer col-span-3 mt-[35px]"></div>

      <TextInput
        label="Email Address(*)"
        :placeholder="'Enter Email Address'"
        v-model="form.contact_person_email"
        :error="v$.contact_person_email.$errors[0]?.$message"
        @blur="
          () => {
            v$.contact_person_email.$touch()
          }
        "
      />

      <PhoneNumberInput
        label="Phone Number(*)"
        v-model:country_code="form.contact_person_phone_iso_code"
        v-model:phone_number="form.contact_person_phone"
        :error="v$.contact_person_phone_iso_code.$errors.length || v$.contact_person_phone.$errors.length ? true : false"
      />

      <!-- Fifth Row -->
      <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[35px]">
        {{ $t('Company Specific Configurations') }}
      </h1>

      <RadioInput label="Device Authentication required?" labelClass="whitespace-nowrap" v-model="form.device_authentication_required" :options="yes_no_options" :asterisk="true" />

      <div class="logout-time-input flex flex-col">
        <label class="text-input block mb-3 text-formLabel whitespace-nowrap">{{ $t('Auto Logout Time for all Users after') }}*</label>
        <div class="wrapper flex items-center gap-[10px]">
          <TextInput
            v-model="form.auto_logout_time"
            :placeholder="'Enter'"
            wrapperClass="w-[40%]"
            :error="v$.auto_logout_time.$errors.length > 0"
            @input="
              () => {
                v$.auto_logout_time.$touch()
              }
            "
          />

          <span class="text-[16px] text-input leading-[19px]">{{ $t('Minutes') }}</span>
        </div>
        <div class="flex flex-col instruction mt-1 overflow-visible">
            <span class="text-[12px] leading-5 text-[#7C7C7C]">Min: 60</span>
            <span class="text-[12px] leading-5 text-[#7C7C7C]">Max: 6000</span>
        </div>
      </div>

      <div class="spacer mt-[35px] col-span-3"></div>

      <template v-if="form.device_authentication_required">
        <MultiSelect
          :options="roles"
          v-model="form.affected_user_roles"
          label="Please select the affected User Roles"
          placeholder="Select User Roles"
          labelClass="whitespace-nowrap"
          :error="v$.affected_user_roles.$errors.length > 0"
          :asterisk="true"
        />
        <div class="spacer mt-[35px] col-span-3"></div>
      </template>

      <!-- Sixth Row -->
      <RadioInput :label="`Organizational elements required?`" labelClass="whitespace-nowrap" v-model="form.hierarchy_elements_required" :options="yes_no_options" :asterisk="true" />

      <template v-if="form.hierarchy_elements_required">
        <div class="spacer mt-10 col-span-3"></div>
        <ButtonGradient class="flex justify-center items-center gap-4" @click="addHierarchyElementModalHandle">
          <PlusIcon />
          <span> {{ $t('Add Element') }} </span>
        </ButtonGradient>
        <div class="spacer mt-[30px] col-span-3"></div>
        <div class="hierarchy_elements grid grid-cols-[40%_40%_20%] gap-y-5 gap-x-[38px]" v-if="form.hierarchy_elements.length">
          <h3 class="title col-span-1 text-input text-[16px] font-semibold">
            {{ $t('Hierarchy Level') }}
          </h3>
          <h3 class="title col-span-1 text-input text-[16px] font-semibold">
            {{ $t('Name') }}
          </h3>
          <div class="spacer col-span-1"></div>
          <HierarchyElement
            v-for="(element, index) in form.hierarchy_elements"
            :key="index"
            :element="element"
            @onUpdate="(element) => (form.hierarchy_elements[index] = element)"
            :hierarchy_levels="hierarchy_levels"
            :used_subordinate_roles="used_subordinate_roles"
          />
        </div>
      </template>

      <div class="spacer mt-[100px] col-span-3"></div>
      <div class="flex col-span-2 gap-[18px]">
        <ButtonGradient class="w-[20%]" type="submit" :disabled="form.busy">
          {{ $t('Save') }}
        </ButtonGradient>

        <ButtonWhite
          class="w-[20%]"
          type="button"
          @click="
            $vfm.show('redirect-modal', {
              title: 'Cancel Customer Company Registration?',
              description: `When you abort, all data is discarded. Are you sure you really want to cancel this customer company registration?`,
            })
          "
        >
          {{ $t('Cancel') }}
        </ButtonWhite>
      </div>
    </form>
  </div>
</template>

<script setup>
import ButtonGradient from '@/components/button/Gradient.vue'
import ButtonWhite from '@/components/button/White.vue'
import TextInput from '@/components/form/TextInput.vue'
import Form from 'vform'
import RadioInput from '@/components/form/RadioInputs.vue'
import { reactive, computed, ref } from '@vue/reactivity'
import { inject, onBeforeMount } from '@vue/runtime-core'
import MultiSelect from '@/components/form/MultiSelect.vue'
import PlusIcon from '@/components/icons/Plus.vue'
import HierarchyElementModificationModal from './components/HierarchyElementModificationModal.vue'
import { trans } from 'laravel-vue-i18n'
import HierarchyElement from './components/HierarchyElement.vue'
import CountrySelect from '@/components/form/CountrySelect.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, maxLength, helpers, requiredIf, minLength } from '@vuelidate/validators'
import PhoneNumberInput from '@/components/form/PhoneNumberInput.vue'
import axios from 'axios'
import { notificationShowStore } from '@/store/notification.js'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'
import {usePermissionStore} from '@/composables/permission'

const notification = notificationShowStore()
const company_roles = storeToRefs(companyRolesStore())

// const { user } = storeToRefs(useUserStore())
const permission = usePermissionStore();

const yes_no_options = [
  { value: 1, label: 'Yes' },
  { value: 0, label: 'No' },
]
const roles = computed(() => {
  if (company_roles.roles.value.length) {
    return company_roles.roles.value.map(({ id, name }) => ({ value: id, label: name }))
  }
  return []
})

const prefix_id = ref('')

const router = useRouter()

const used_subordinate_roles = computed(() => {
  return form.hierarchy_elements.map((el) => (el.hierarchy_level === null ? [] : el.direct_subordinated_role)).flat()
})

const hierarchy_levels = computed(() => {
  let levels = [1, 2, 3, 4, 5, 6, 7, 8, , 9, 10].filter((n) => !form.hierarchy_elements.some((el) => el.hierarchy_level == n))
  levels = levels.map((i) => ({ value: i, label: i }))
  levels.unshift({ label: trans('None'), value: null })
  return levels
})

const $vfm = inject('$vfm')

const form = reactive(
  new Form({
    name: '',
    alias_name: '',
    street_name: '',
    street_number: '',
    zip_code: '',
    city: '',
    country_iso_code: '',
    contact_person_first_name: '',
    contact_person_last_name: '',
    contact_person_email: '',
    contact_person_phone_iso_code: '',
    contact_person_phone: '',
    device_authentication_required: null,
    auto_logout_time: '',
    hierarchy_elements_required: null,
    affected_user_roles: [],
    hierarchy_elements: [],
  })
)

const country_iso_code = computed({
  get: () => form.country_iso_code,
  set: (value) => {
    form.country_iso_code = value
    if (!form.contact_person_phone_iso_code || !form.contact_person_phone) form.contact_person_phone_iso_code = value
  },
})

const rules = {
  name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 50 }), maxLength(50)),
  },
  alias_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 50 }), maxLength(50)),
  },
  street_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
  },
  street_number: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
  },
  city: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
  },
  country_iso_code: {
    required: helpers.withMessage('', required),
  },
  contact_person_first_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  contact_person_last_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  contact_person_email: {
    required: helpers.withMessage('', (value) => (form.contact_person_phone ? true : value.length ? true : false)),
    email: helpers.withMessage('Invalid Email format', email),
  },
  contact_person_phone_iso_code: {
    reuqired: helpers.withMessage('', (value) => (form.contact_person_email ? true : value.length ? true : false)),
  },
  contact_person_phone: {
    reuqired: helpers.withMessage('', (value) => (form.contact_person_email ? true : value.length ? true : false)),
    valid: helpers.withMessage('', helpers.regex(/^\d*$/)),
    maxLength: helpers.withMessage('', maxLength(15)),
    minLength: helpers.withMessage('', minLength(4)),
  },
  device_authentication_required: {
    required: helpers.withMessage('', required),
  },
  hierarchy_elements_required: {
    required: helpers.withMessage('', required),
  },
  affected_user_roles: {
    required: helpers.withMessage(
      '',
      requiredIf(() => form.device_authentication_required == true)
    ),
  },
  auto_logout_time: {
    validFormat: helpers.withMessage('', helpers.regex(/^[\d]+$/u)),
    isBetween: helpers.withMessage('', (value) => {
      if (value) {
        return parseInt(value) >= 60 && parseInt(value) <= 6000 ? true : false
      }
      return true
    }),
    reuqired: helpers.withMessage('', required),
  },
  zip_code: {
    required: helpers.withMessage('', required),
    validFormat: helpers.withMessage('', (value) => {
      if (form.country_iso_code == 'ch') return /^[\d]*$/.test(value)
      return value.length < 1 ? true : /^[\d\p{L}\p{M}\p{Zs}\-]+$/u.test(value)
    }),
    maxLength: helpers.withMessage('', maxLength(4)),
    minLength: helpers.withAsync('', minLength(1)),
    // validLength: helpers.withMessage('', (value) => {
    //   if (form.country_iso_code == 'ch') return value.length < 5
    //   return value.length < 1 ? true : value.length < 11
    // }),
  },
}

const v$ = useVuelidate(rules, form)

const submit = async () => {
  v$.value.$touch()
  if (v$.value.$invalid) {
    $vfm.show('field-missing')
    return
  }
  try {
    const res = await form.post(route('customer-companies.store'))
    router.push({ name: 'customer-company-index' })
    notification.success(
      trans('The Customer Company was successfully created.', {
        name: res.data.name,
      })
    )
  } catch (error) {
    console.log(error, ['error'])
  }
}

const addHierarchyElementModalHandle = () => {
  const options = {
    component: HierarchyElementModificationModal,
    bind: {
      title: trans('Add Organizational Element'),
      hierarchy_levels: hierarchy_levels.value,
      used_subordinate_roles: used_subordinate_roles.value,
    },
    on: {
      saveElement: (element) => {
        form.hierarchy_elements.push(element)
      },
    },
  }
  $vfm.show(options)
}

onBeforeMount(() => {
  getNextPrefixId('CustomerCompany')
})

const getNextPrefixId = async (model_name) => {
  try {
    const res = await axios.get(route('next-prefix-id', { modelName: model_name }))
    prefix_id.value = res.data
  } catch (error) {
    console.log(error)
  }
}

if( !permission.hasPermission('customer-companye:edit')){
    // console.log('has access')
    router.push({ name: '403' })
}
</script>

<style scoped></style>

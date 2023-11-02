<template>
  <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[42px] py-[43px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]">
    <Back class="mb-[33px]" :show_modal="!useIsEqual(form.data(), form.originalData)" />
    <h1 class="text-formHeading text-title font-poppins mb-[60px]">
      {{ $t('Edit Customer Company') }}
    </h1>

    <form class="form grid grid-cols-3 gap-x-[251px]" @submit.prevent="submit" v-if="customer_company">
      <!-- non editable infos -->
      <div class="flex flex-col">
        <h2 class="label text-formLabel font-inter text-input mb-[10px]">
          {{ $t('Creation Date ') }}
        </h2>
        <p class="text-value text-4 font-semibold font-inter leading-[19px]" v-date-format="customer_company.created_at"></p>
      </div>

      <div class="flex flex-col">
        <h2 class="label text-formLabel font-inter text-input mb-[10px]">
          {{ $t('Company ID') }}
        </h2>
        <p class="text-value text-4 font-semibold font-inter leading-[19px]">
          {{ customer_company.prefix_id }}
        </p>
      </div>
      <div class="spacer col-span-3 mb-11"></div>
      <!-- First Row -->

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
        @input="v$.zip_code.$touch()"
        :maxLength="5"
        :error="v$.zip_code.$errors.length ? (v$.zip_code.maxLength.$invalid ? $t('Maximum :length characters possible', { length: 4 }) : true) : false"
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

      <CountrySelect v-model="country_iso_code" label="Country" :asterisk="true" placeholder="Select Country" :error="v$.country_iso_code.$errors.length > 0" />

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
            :error="(form.errors.auto_logout_time ? true : false) || v$.auto_logout_time.$errors.length > 0"
            @input="
              () => {
                v$.auto_logout_time.$touch()
                delete form.errors.auto_logout_time
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
          :asterisk="true"
          :error="v$.affected_user_roles.$errors.length > 0"
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

        <HierarchyElementWithControl v-if="customer_company.hierarchy_elements?.length" v-model="form.hierarchy_elements" :company_name="customer_company.name" />
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
              title: 'Discard Changes?',
              description: `If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?`,
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
import { trans } from 'laravel-vue-i18n'
import HierarchyElementWithControl from './components/HierarchyElementWithControl.vue'
import CountrySelect from '@/components/form/CountrySelect.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, maxLength, helpers, requiredIf, minLength } from '@vuelidate/validators'
import PhoneNumberInput from '../../components/form/PhoneNumberInput.vue'
import Back from '@/components/form/Back.vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { notificationShowStore } from '@/store/notification.js'
import { useIsEqual } from '@/composables/utils.js'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import PlusIcon from '@/components/icons/Plus.vue'
import HierarchyElementModificationModal from './components/HierarchyElementModificationModal.vue'
import {usePermissionStore} from '@/composables/permission'
const notification = notificationShowStore()
const permission = usePermissionStore();


const yes_no_options = [
  { value: 1, label: 'Yes' },
  { value: 0, label: 'No' },
]
const company_roles = storeToRefs(companyRolesStore())
const roles = computed(() => {
  if (company_roles.roles.value.length) {
    return company_roles.roles.value.map(({ id, name }) => ({ value: id, label: name }))
  }
  return []
})

const used_subordinate_roles = computed(() => {
  return form.hierarchy_elements.map((el) => (el.hierarchy_level === null ? [] : el.direct_subordinated_role)).flat()
})

const hierarchy_levels = computed(() => {
  let levels = [1, 2, 3, 4, 5, 6, 7, 8, , 9, 10].filter((n) => !form.hierarchy_elements.some((el) => el.hierarchy_level == n))
  levels = levels.map((i) => ({ value: i, label: i }))
  levels.unshift({ label: trans('None'), value: null })
  return levels
})

const vueRoute = useRoute()
const router = useRouter()

const $vfm = inject('$vfm')
const customer_company = reactive({})
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
    hierarchy_elements_required: 0,
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
      if (form.country_iso_code == 'ch') return /^[\d]+$/u.test(value)
      return value.length < 1 ? true : /^[\d\p{L}\p{M}\p{Zs}\-]+$/u.test(value)
    }),
    maxLength: helpers.withMessage('', maxLength(4)),
    minLength: helpers.withAsync('', minLength(1)),
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
    //clear unnecessary data
    const hierarchy_elements = form.hierarchy_elements.map((el) => ({
      id: el.id,
      status: el.status,
      name: el.name,
      hierarchy_level: el.hierarchy_level,
      responsible_role: el.responsible_role,
      direct_subordinated_role: el.direct_subordinated_role,
    }))
    const data = form.data()
    data.hierarchy_elements = hierarchy_elements

    const res = await axios.put(
      route('customer-companies.update', {
        customer_company: customer_company.id,
      }),
      data
    )

    router.push({ name: 'customer-company-index' })
    notification.success(
      trans('The Customer Company “:name” was successfully updated.', {
        name: res.data.name,
      })
    )
  } catch (err) {
    if (err.response?.status == 422) {
      form.errors.errors = err.response.data.errors
    }
    console.log(err)
  }
}

onBeforeMount(() => {
  getCompanyDetails()
})

const getCompanyDetails = async () => {
  try {
    const res = await axios.get(
      route('customer-companies.show', {
        customer_company: vueRoute.params.id,
      })
    )
    Object.assign(customer_company, res.data)

    //assign customer company value to form
    Object.keys(form.data()).forEach((key) => {
      if (customer_company.hasOwnProperty(key)) {
        form[key] = customer_company[key]
        form.originalData[key] = customer_company[key]
      }
    })
  } catch (error) {
    console.log(error)
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
        form.hierarchy_elements.push({...element, status: 'Active'})
      },
    },
  }
  $vfm.show(options)
}


if( !permission.hasPermission('customer-companye:edit')){
    // console.log('has access')
    router.push({ name: '403' })
}
</script>

<style scoped></style>

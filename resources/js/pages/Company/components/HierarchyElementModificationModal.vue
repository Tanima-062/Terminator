<template>
  <vue-final-modal v-slot="{ close }" classes="flex justify-center items-center" content-class="bg-white px-[77px] pt-12 pb-[54px] rounded-sm w-[621px]" :keep-overlay="true" :click-to-close="false">
    <form class="w-full h-full" @submit.prevent="submit">
      <h1 class="text-[20px] mb-[42px] text-heading leading-6 font-semibold">
        {{ title }}
      </h1>

      <SingleSelect
        label="Hierarchy Level"
        placeholder="Select Hierarchy Level"
        asterisk="true"
        :options="hierarchy_levels"
        v-model="form.hierarchy_level"
        togglerClass="rounded-bl-lg"
        optionsClass="w-[133px] right-0 max-h-max"
        class="mb-10"
        :error="v$.hierarchy_level.$errors.length > 0"
      />

      <TextInput class="mb-10" label="Name" :asterisk="true" placeholder="Enter Name" v-model="form.name" :error="v$.name.$errors.length > 0" @input="v$.name.$touch()" maxLength="41" />

      <div class="wrapper">
        <MultiSelect
          :options="roles"
          v-model="form.responsible_role"
          :label="{ text: `Responsible Role:slashs`, replace: { slash: '/' } }"
          :placeholder="{
            text: 'Select Responsible Role:slashs',
            replace: { slash: '/' },
          }"
          labelClass="whitespace-nowrap"
          class="mb-10"
          :asterisk="form.hierarchy_level !== null && true"
          :optional="form.hierarchy_level === null && true"
          :error="v$.responsible_role.$errors.length > 0"
        />
      </div>
      <div class="wrapper">
        <MultiSelect
          :options="available_roles"
          v-model="form.direct_subordinated_role"
          :label="{ text: `Directly subordinated Role:slashs`, replace: { slash: '/' } }"
          :placeholder="{
            text: 'Select Directly subordinated Role:slashs',
            replace: { slash: '/' },
          }"
          labelClass="whitespace-nowrap"
          class="mb-[100px]"
          :asterisk="form.hierarchy_level !== null && true"
          :optional="form.hierarchy_level === null && true"
          :error="v$.direct_subordinated_role.$errors.length > 0"
        />
      </div>

      <div class="btns flex justify-center gap-[18px]">
        <ButtonGradient class="w-full h-[48px]" type="submit">
          {{ $t('Save') }}
        </ButtonGradient>

        <ButtonWhite class="w-full" @click="close" type="button">
          {{ $t('Cancel') }}
        </ButtonWhite>
      </div>
    </form>
  </vue-final-modal>
</template>

<script setup>
import { reactive, computed } from '@vue/reactivity'
import ButtonGradient from '@/components/button/Gradient.vue'
import ButtonWhite from '@/components/button/White.vue'
import SingleSelect from '@/components/form/SingleSelect.vue'
import MultiSelect from '@/components/form/MultiSelect.vue'
import TextInput from '@/components/form/TextInput.vue'
import { inject, watch } from '@vue/runtime-core'
import useVuelidate from '@vuelidate/core'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import { required, helpers, maxLength } from '@vuelidate/validators'
import vForm from 'vform'

const props = defineProps({
  title: {
    type: String,
    required: true,
  },

  hierarchy_levels: {
    type: Array,
    required: true,
  },

  used_subordinate_roles: {
    type: Array,
    required: true,
  },

  value: {
    type: Object,
    required: false,
    default: () => ({
      hierarchy_level: '',
      name: '',
      responsible_role: [],
      direct_subordinated_role: [],
    }),
  },

  submit_to_backend: {
    type: Boolean,
    default: false,
  },
})

const company_roles = storeToRefs(companyRolesStore())
const roles = computed(() => {
  if (company_roles.roles.value.length) {
    return company_roles.roles.value.map(({ id, name }) => ({ value: id, label: name }))
  }
  return []
})

const available_roles = computed(() => {
  if (form.hierarchy_level === null) return roles.value
//   console.log('not null', [props.used_subordinate_roles])
  const my_used_roles = props.used_subordinate_roles
    .filter((role) => props.value.direct_subordinated_role.includes(role) && props.value.hierarchy_level !== null)
    .map((role) => ({ value: role, label: roles.value.find(({ value }) => role == value).label }))

  return [...roles.value?.filter(({ value }) => !props.used_subordinate_roles.includes(value)), ...my_used_roles]
})

const emit = defineEmits(['saveElement'])
const $vfm = inject('$vfm')

const form = reactive(new vForm({ ...props.value }))

const rules = {
  hierarchy_level: {
    required: helpers.withMessage('', (value) => value !== ''),
  },
  name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage('', maxLength(40)),
  },
  responsible_role: {
    required: helpers.withMessage('', () => (form.hierarchy_level !== null ? form.responsible_role.length : true)),
  },
  direct_subordinated_role: {
    required: helpers.withMessage('', () => (form.hierarchy_level !== null ? form.direct_subordinated_role.length : true)),
  },
}

const v$ = useVuelidate(rules, form)

watch(
  () => form.hierarchy_level,
  (cur) => {
    form.direct_subordinated_role = []
  }
)

const submit = async () => {
  v$.value.$touch()
  if (v$.value.$invalid) {
    return
  }
  if (props.submit_to_backend && props.value.id) {
    try {
      const res = await form.put(route('hierarchy-elements.update', { hierarchy_element: props.value.id }))
      emit('saveElement', { ...form.data(), ...res.data })
      $vfm.hideAll()
    } catch (error) {
      console.log(error)
    }
    return
  }

  emit('saveElement', form.data())
  $vfm.hideAll()
}
</script>

<template>
  <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]">
    <Back class="mb-[33px]" :show_modal="!useIsEqual(form.data(), form.originalData)" />
    <h1 class="text-formHeading text-title font-poppins mb-[60px]">
      {{ $t('Edit organizational element') }}
    </h1>

    <form class="form grid grid-cols-3 gap-x-[21px]" @submit.prevent="submit">
      <div class="wrappeer col-span-3 grid grid-cols-3" :class="[form.parent_hierarchy?.length ? 'gap-y-[48px]' : 'gap-y-6']">
        <div class="flex flex-col">
          <h2 class="label text-formLabel font-inter text-input mb-[10px]">{{ $t('ID') }}*</h2>
          <p class="text-value text-4 font-semibold font-inter leading-[19px]">
            {{ organization_element.prefix_id }}
          </p>
        </div>

        <div class="flex flex-col">
          <h2 class="label text-formLabel font-inter text-input mb-[10px]">
            {{ $t('Creation Date') }}
          </h2>
          <p class="text-value text-4 font-semibold font-inter leading-[19px]" v-date-format="organization_element.created_at"></p>
        </div>

        <SingleSelect
          class="max-w-[290px]"
          label="Type"
          v-model="form.type_id"
          :options="hierarchy_levels"
          valueKey="id"
          labelKey="name"
          placeholder="Select type"
          :asterisk="true"
          :error="v$.type_id.$errors.length > 0"
          @onUpdate="
            () => {
              form.subordinate_users = []
              form.responsible_users = []
            }
          "
        />
        <SingleSelect
          class="max-w-[290px]"
          valueKey="id"
          labelKey="name"
          v-if="parent_hierarchy_levels.length"
          v-for="(hierarchy, index) in parent_hierarchy_levels"
          :options="hierarchy.organization_elements"
          :key="index"
          :label="hierarchy.name"
          :placeholder="`${hierarchy.name} ${$t('Select')}`"
          v-model="form.parent_hierarchy.find(({ hierarchy_id }) => hierarchy_id == hierarchy.id).organization_element_id"
          :asterisk="hierarchy.hierarchy_level === null ? false : true"
          :error="form.errors.has(`parent_hierarchy.${index}`)"
          @onUpdate="form.errors.clear(`parent_hierarchy.${index}`)"
        />

        <div class="spacer col-span-3" v-if="!form.parent_hierarchy?.length"></div>

        <TextInput v-model="form.name" label="Name" placeholder="Enter Name" :asterisk="true" class="max-w-[290px]" maxLength="41" :error="v$.name.$errors[0]?.$message" @input="v$.name.$touch()" />

        <MultiSelect label="Responsible:slashr" v-model="form.responsible_users" :options="responsible_users" placeholder="Responsible:slashr Select" class="max-w-[290px]" />
      </div>

      <div class="spacer col-span-3 mb-[80px]"></div>

      <div class="flex flex-col">
        <h2 class="label text-formLabel font-inter text-input mb-[30px] text-[20px]">
          {{ $t('Directly subordinate users') }}
        </h2>
        <ButtonGradient class="w-[286px]" type="button" @click="addInternalUser">
          {{ $t('Assign internal users') }}
        </ButtonGradient>
      </div>
      <div class="spacer col-span-3"></div>

      <SelectedInternalUsersView
        v-if="form.subordinate_users.length"
        :users="subordinate_users"
        :value="form.subordinate_users"
        :roles="available_roles"
        @updateSubordinateUsers="(users) => (form.subordinate_users = users)"
        class="mt-[44px]"
      />
      <div class="spacer col-span-3 mb-[100px]"></div>

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

    <!-- Modals -->
  </div>
</template>

<script setup>
import ButtonGradient from '@/components/button/Gradient.vue'
import ButtonWhite from '@/components/button/White.vue'
import TextInput from '@/components/form/TextInput.vue'
import Form from 'vform'
import { reactive, ref, computed } from '@vue/reactivity'
import { inject, onBeforeMount, watch } from '@vue/runtime-core'
import MultiSelect from './components/MultiSelect.vue'
import SingleSelect from '@/components/form/SingleSelect.vue'
import { trans } from 'laravel-vue-i18n'
import { useVuelidate } from '@vuelidate/core'
import { required, email, maxLength, helpers } from '@vuelidate/validators'
import { notificationShowStore } from '@/store/notification.js'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import { useRoute, useRouter } from 'vue-router'
import InternalUserModificationModal from './components/InternalUserModificationModal.vue'
import SelectedInternalUsersView from './components/SelectedInternalUsersView.vue'
import Back from '@/components/form/Back.vue'
import { useIsEqual } from '@/composables/utils.js'
import { toRaw } from 'vue'
import {usePermissionStore} from '@/composables/permission'
const permission = usePermissionStore();

const vueRoute = useRoute()
const router = useRouter()
const $vfm = inject('$vfm')

const notification = notificationShowStore()
const company_roles = storeToRefs(companyRolesStore())

const organization_element = reactive({})
const hierarchy_levels = ref([])

const subordinate_users = ref([])
const responsible_users = ref([])

const form = reactive(
  new Form({
    type_id: '',
    name: '',
    responsible_users: [],
    subordinate_users: [],
    parent_hierarchy: [],
  })
)

const parent_hierarchy_levels = computed(() => {
  if (!form.type_id) return []

  const selected_hierarchy = hierarchy_levels.value.find((level) => level.id == form.type_id)
  if (!selected_hierarchy) return []

  const parents = hierarchy_levels.value.filter((level) => {
    if (selected_hierarchy.id == level.id) return
    if (level.organization_elements.length < 1) return
    if (selected_hierarchy.hierarchy_level == null) {
      return level.hierarchy_level == null
    } else {
      return level.hierarchy_level < selected_hierarchy.hierarchy_level && level.hierarchy_level !== null
    }
  })

  form.parent_hierarchy = form.parent_hierarchy.filter(({ hierarchy_id }) => parents.some(({ id }) => id == hierarchy_id))
  for (const key in parents) {
    const organization_element_id = parents[key].organization_elements.length == 1 ? parents[key].organization_elements[0].id : ''
    if (!form.parent_hierarchy.some(({ hierarchy_id }) => hierarchy_id == parents[key].id)) {
      form.parent_hierarchy.push({ organization_element_id, hierarchy_id: parents[key].id })
    }
  }
  return parents
})

const available_roles = computed(() => {
  return company_roles.roles.value.filter(({ id }) => subordinate_users.value.some(({ role }) => role == id)).map(({ id, name }) => ({ value: id, label: name }))
})

const rules = {
  name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 40 }), maxLength(40)),
  },
  type_id: {
    required: helpers.withMessage('', required),
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
    const res = await form.put(route('organization-elements.update', { organization_element: organization_element.id }))
    router.push({ name: 'organization-element-index' })
    notification.success({ text: 'The :name organizational element was updated successfully.', replace: { name: res.data.name } })
  } catch (error) {
    $vfm.show('field-missing')
    console.log(error, ['error'])
  }
}

const getHierarchyLevels = async () => {
  try {
    const res = await axios.get(route('hierarchy-elements.index', { status: 'Active' }))
    hierarchy_levels.value = res.data
  } catch (error) {
    console.log(error)
  }
}

const getInternalUsers = async (id) => {
  try {
    const res = await axios.get(route('hierarchy.internal-users', { hierarchy_element: id }))
    subordinate_users.value = res.data.filter(({ internal_user_type }) => internal_user_type == 'direct_subordinate')
    responsible_users.value = res.data.filter(({ internal_user_type }) => internal_user_type == 'responsible')
  } catch (error) {
    console.log(error)
  }
}

const getData = async () => {
  try {
    const { data } = await axios.get(route('organization-elements.show', { organization_element: vueRoute.params.id }))
    data.parent_hierarchy = data.parent_organization_elements.map(({ id, type_id }) => {
      return { organization_element_id: id, hierarchy_id: type_id }
    })
    Object.assign(organization_element, data)

    //assign customer company value to form
    form.originalData['subordinate_users'] = form['subordinate_users'] = organization_element.direct_subordinate_role_users.map(({ user_id }) => user_id)
    form.originalData['responsible_users'] = form['responsible_users'] = organization_element.responsible_role_users.map(({ user_id }) => user_id)

    Object.keys(form.data()).forEach((key) => {
      if (organization_element.hasOwnProperty(key)) {
        form[key] = organization_element[key]
        if (typeof organization_element[key] == 'object') {
          form.originalData[key] = structuredClone(toRaw(organization_element[key]))
        } else {
          form.originalData[key] = organization_element[key]
        }
      }
    })
  } catch (error) {
    console.log(error)
    // router.push({ name: '404' })
        if(error.response.status == 404){
            router.push({name: '404'})
        }
        if(error.response.status == 403){
            router.push({ name: '403' })
        }
  }
}

const addInternalUser = () => {
  const options = {
    component: InternalUserModificationModal,
    bind: {
      title: 'Assign internal users',
      value: form.subordinate_users,
      roles: available_roles.value,
      users: subordinate_users.value,
    },
    on: {
      save: (users) => (form.subordinate_users = users),
    },
  }
  $vfm.show(options)
}

watch(
  () => form.type_id,
  (id) => {
    getInternalUsers(id)
  }
)

onBeforeMount(async () => {
  await getHierarchyLevels()
  await getData()
})

if( !permission.hasPermission('organization-element:edit')){
    // console.log('has access')
    router.push({ name: '403' })
}

</script>

<style scoped></style>

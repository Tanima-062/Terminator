<template>
  <div class="content px-6">
    <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[42px] py-[43px] overflow-y-auto pr-[127px] rounded-[15px] min-h-[89vh]">
      <Back class="mb-[33px]" />

      <div class="mb-[40px] xs:mb-[20px]">
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">{{ $t('Organization element details') }}</h1>
      </div>

      <template v-if="organization_element">
        <div class="flex">
          <div class="w-1/3">
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
              {{ $t('ID') }}
            </h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
              {{ organization_element.prefix_id }}
            </p>
          </div>

          <div class="w-1/3">
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
              {{ $t('Creation Date') }}
            </h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px]" v-date-format="organization_element.created_at"></p>
          </div>

          <div class="w-1/3">
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
              {{ $t('Name') }}
            </h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ organization_element.name }}</p>
          </div>
        </div>

        <div class="grid grid-cols-3 grid-rows-auto mt-11 gap-y-11">
          <div>
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
              {{ $t('Type') }}
            </h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
              {{ organization_element.hierarchy_type.name }}
            </p>
          </div>

          <div v-for="(parent, index) in organization_element.parent_organization_elements">
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">{{ parent.hierarchy_type.name }}</h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ parent.name }}</p>
          </div>

          <div>
            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
              {{ $t('Responsible:slashr') }}
            </h2>
            <p class="text-value text-4 font-[400px] font-inter leading-[19px] flex gap-2">
                <p class="text-value text-4 font-[400px] font-inter leading-[19px]" v-if="organization_element.responsible_role_users[0]"  >{{ organization_element.responsible_role_users[0].user.full_name }}</p>
              <PillTooltip :length="organization_element.responsible_role_users.length - 1" :items="organization_element.responsible_role_users.slice(1)">
                <template #item="{ item }">
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ item.user.full_name }}</p>
                </template>
              </PillTooltip>
            </p>
          </div>
        </div>

        <h2 class="label text-formLabel font-inter text-input mb-5 mt-[60px] text-[20px]">
          {{ $t('Directly subordinate users') }}
        </h2>


        <SelectedInternalUsersView :users="subordinate_users" :value="selected_subordinate_users" :roles="available_roles" :hide_edit_btn="true" />

        <ButtonGradient class="w-max flex gap-[14px] items-center justify-center p-0 mt-[100px]" type="submit" @click="$router.push({ name: 'organization-element-edit', id: organization_element.id })" v-if="hasPermission('organization-element:edit')">
          <PencilIcon/>
          <span class="m-auto">{{ $t('Edit') }}</span>
        </ButtonGradient>
      </template>
    </div>
  </div>
</template>

<script setup>
import ButtonGradient from '@/components/button/Gradient.vue'
import PencilIcon from '@/components/icons/PencilIcon.vue'
import SelectedInternalUsersView from './components/SelectedInternalUsersView.vue'
import Back from '@/components/form/Back.vue'
import { useRoute, useRouter } from 'vue-router'
import { ref, computed } from '@vue/reactivity'
import axios from 'axios'
import { onBeforeMount } from '@vue/runtime-core'
import { companyRolesStore } from '@/store/company_roles.js'
import { storeToRefs } from 'pinia'
import PillTooltip from '@/components/utils/PillTooltip.vue'

const vueRoute = useRoute()
const router = useRouter()

const company_roles = storeToRefs(companyRolesStore())

const organization_element = ref()

const subordinate_users = ref([])
const selected_subordinate_users = ref([])

const available_roles = computed(() => {
  return company_roles.roles.value.filter(({ id }) => organization_element.value?.direct_subordinate_role_users.some(({ role }) => role == id)).map(({ id, name }) => ({ value: id, label: name }))
})

const getData = async () => {
  try {
    const { data } = await axios.get(route('organization-elements.show', { id: vueRoute.params.id }))
    organization_element.value = data
    selected_subordinate_users.value = data.direct_subordinate_role_users.map(({ user_id }) => user_id)
    subordinate_users.value = data.direct_subordinate_role_users.map(({ user }) => ({ label: user.full_name, role: user.internal_user.roles_id, value: user.id, internal_user_id: user.internal_user.id}))
  } catch (error) {
    // router.push({ name: '404' })
    if(error.response.status == 404){
        router.push({name: '404'})
        }
        if(error.response.status == 403){
            router.push({ name: '403' })
        }
  }
}

onBeforeMount(async () => {
  await getData()
})
</script>

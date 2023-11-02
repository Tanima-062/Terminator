<template>
  <div class="tr flex gap-x-2">
    <div class="td p-0 w-[15%]" :class="{ 'w-[12%]': user.type == 'system_admin' }" v-date-format="organization_element.created_at"></div>
    <div class="td p-0 td-link w-[15%]" v-if="user.type == 'system_admin'">
      <a class="text-16 text-[#13A3E5]" :href="$router.resolve({ name: 'customer-company-show', params: { id: organization_element.customer_company_id } }).href" target="_blank">
        {{ organization_element.customer_company_name }}</a
      >
    </div>
    <div class="td p-0 td-link w-[15%]" :class="{ 'w-[13%]': user.type == 'system_admin' }">
      <router-link
        :to="{
          name: 'organization-element-show',
          params: { id: organization_element.id },
        }"
      >
        {{ organization_element.name }}</router-link
      >
    </div>
    <div class="td p-0 w-[15%]">{{ organization_element.hierarchy_name }}</div>
    <div class="td p-0 w-[20%] flex gap-2" :class="{ 'w-[15%]': user.type == 'system_admin' }">
      <a
        class="text-16 text-[#13A3E5]"
        target="_blank"
        v-if="responsible_users[0]"
        :href="$router.resolve({ name: 'internal-user-show', params: { id: responsible_users[0].id } }).href"
      >
        {{ responsible_users[0].full_name }}</a
      >
      <PillTooltip :length="responsible_users.length - 1" :items="responsible_users.slice(1)">
        <template #item="{ item }">
          <a class="text-16 text-[#13A3E5]" :href="$router.resolve({ name: 'internal-user-show', params: { id: item.id } }).href" target="_blank"> {{ item.full_name }}</a>
        </template>
      </PillTooltip>
    </div>
    <div class="td p-0 w-[10%]">
      <Status :organization_element="organization_element" />
    </div>
    <div class="td p-0 flex justify-center w-[20%]">
      <MenuBarVue :organization_element="organization_element" @onDeactivate="responsible_users = []" />
    </div>
    <div class="spacer w-[5%]" v-if="user.type !== 'system_admin'"></div>
  </div>
</template>

<script setup>
import MenuBarVue from './MenuBar.vue'
import Status from './Status.vue'
import { useUserStore } from '@/store/user.js'
import { storeToRefs } from 'pinia'
import { ref } from 'vue'
import PillTooltip from '@/components/utils/PillTooltip.vue'

const props = defineProps({
  organization_element: {
    type: Object,
    required: true,
  },
})

const responsible_users = ref(props.organization_element.responsible_users)
const userStore = useUserStore()
const { user } = storeToRefs(userStore)

</script>

<style lang="scss" scoped></style>

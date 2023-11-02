<template>
  <div class="row-span-2 col-span-1 mr-2 ml-6 flex flex-col gap-6 pt-[30px]">
    <template v-for="({ name, target, icon, activeCondition = () => {},  condition = true }, index) in routes" :key="index">
      <SidebarLink v-if="typeof condition == 'function' ? condition() : condition" :to="{ name: target }" :collapse="$attrs.collapse" :activeCondition="activeCondition">
        <template v-slot:icon="{ active }">
          <component :is="icon" :active="active"></component>
        </template>

        <template #name
          ><div>{{ $t(name) }}</div></template
        >
      </SidebarLink>
    </template>
  </div>
</template>

<script setup>
import SidebarLink from "./SidebarLink.vue";
import CustomerCompanyIcon from "../../components/icons/Sidebar/CustomerCompany.vue";
import AdministratorsIcon from "../../components/icons/Sidebar/Administrators.vue";
import InternalUsersIcon from "../../components/icons/Sidebar/InternalUsers.vue";
import OrganizationElementIcon from "../../components/icons/Sidebar/OrganizationElement.vue";
import Dashboard from "../../components/icons/Sidebar/Dashboard.vue";
import BrokerIcon from "../../components/icons/Sidebar/Broker.vue";
import ContactDataRecordsIcon from "../../components/icons/Sidebar/ContactDataRecords.vue";
import { reactive, markRaw } from "@vue/reactivity";
import { useUserStore } from "@/store/user";
import { storeToRefs } from "pinia";
import WorkflowIcon from "@/components/icons/Sidebar/Workflow.vue";
import IntermediaryIcon from "@/components/icons/Sidebar/Intermediary.vue";
import TerminIcon from "@/components/icons/Sidebar/TerminIcon.vue";
import { computed } from "vue";
import {useRoute} from 'vue-router'
import { startsWith } from "lodash";

const { hasAnyPermissions, hasPermission, ...userStore } = useUserStore();
const { user } = storeToRefs(userStore);
const vRoute = useRoute();

const contactDataRecordRoute = computed(()=> {
    const userValue = user.value

    if(userValue.type == 'internal_user' && userValue.role == 'Quality controller'){
        if(userValue.alignment.includes('1') && userValue.alignment.includes('2')) {
            return 'contact-data-records'
        }else if(userValue.alignment.includes('2')){
            return 'contact-data-records-termin-index'
        }

    }

    return 'contact-data-records'
})

// console.log(contactDataRecordRoute.value)

const routes = reactive([
    {
        name: "Dashboard",
        target: "dashboard",
        icon: markRaw(Dashboard),
        condition: hasAnyPermissions(["dashboard-view"]),
    },
    {
        name: "Customer Companies",
        target: "customer-company",
        icon: markRaw(CustomerCompanyIcon),
        condition: hasAnyPermissions([
            "customer-companye:viewAny",
            "customer-companye:edit",
        ]),
    },
    {
        name: "Administrators",
        target: "customer-company-admin-index",
        icon: markRaw(AdministratorsIcon),
        condition: hasAnyPermissions([
            "customer-company-admin:view",
            "customer-company-admin:edit",
        ]),
    },
    {
        name: "Organization",
        target: "organization-element-index",
        icon: markRaw(OrganizationElementIcon),
        condition: () => {
            return (
                hasPermission("organization-element:view") &&
                user.value.customer_company?.hierarchy_elements_required
            );
        },
    },
    {
        name: "Internal user",
        target: "internal-user-index",
        icon: markRaw(InternalUsersIcon),
        condition: hasAnyPermissions([
            "internal-user:create",
            "internal-user:edit",
            "internal-user:view",
        ]),
    },
    {
        name: "Broker & Broker User",
        target: "broker",
        icon: markRaw(BrokerIcon),
        activeCondition: () => vRoute.path.startsWith('/broker-users'),
        condition: hasAnyPermissions([
            "broker:view",
            "broker:edit",
            "broker-user:view",
            "broker-user:edit",
        ]),
    },
    {
        name: "Intermediary",
        target: "intermediary",
        icon: markRaw(IntermediaryIcon),
        condition: hasAnyPermissions([
            // "broker-user:view",
            // "broker-user:edit",
            "intermediares.view",
            "intermediares.create",
            "intermediares.edit",
        ]),
    },
    {
        name: "Contact Data Records",
        target: contactDataRecordRoute,
        icon: markRaw(ContactDataRecordsIcon),
        condition: hasAnyPermissions([
            "contact-data-record:view",
            "contact-data-record:edit",
        ]),
    },
    {
        name: "Workflow Settings",
        target: "workflow-settings-setps-and-costs",
        icon: markRaw(WorkflowIcon),
        condition: hasAnyPermissions(["workflow-settings"]),

        //workflow-settings
    },
    {
        name: "Appointments",
        target: "termins",
        icon: markRaw(TerminIcon),
        condition: hasAnyPermissions(['termin.view','termin.edit',]),

        //workflow-settings
    },
]);
</script>

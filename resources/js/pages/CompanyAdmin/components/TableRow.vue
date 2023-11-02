<template>
    <div class="tr">
        <div class="td " style="width:15%">{{ formateDate(customer_company_admin.created_at) }}</div>
        <div class="td" style="width:20%">{{ customer_company_admin.customer_company.name }}</div>
        <div class="td td-link" style="width:15%">
            <router-link :to="{name: 'customer-company-admin-show', params:{id: customer_company_admin.id}}"> {{ customer_company_admin.user.name }}</router-link>
        </div>
        <div class="td" style="width:18%">{{ customer_company_admin.user.email }}</div>
        <div class="td" style="width:13%">{{ customer_company_admin.full_phone_number }}</div>
        <div class="td" style="width:16%">
             <Status :customer_company_admin="customer_company_admin" />
            </div>
        <div class="td" style="width:3%">
            <MenuBarVue :customer_company_admin="customer_company_admin" />
        </div>
    </div>
</template>

<script>
    import MenuBarVue from './MenuBar.vue';
    import Status from './Status.vue'
    export default {
        props: {
            customer_company_admin: {
                type: Object,
                required: true
            }
        },
        components: {
            MenuBarVue,
            Status
        }
    }
</script>

<style lang="scss" scoped>

</style>

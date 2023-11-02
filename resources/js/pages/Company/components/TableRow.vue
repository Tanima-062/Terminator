<template>
    <div class="tr">
        <div class="td " style="width:15%">{{ formateDate(customer_company.created_at) }}</div>
        <div class="td" style="width:15%">{{ customer_company.prefix_id }}</div>
        <div class="td td-link" style="width:20%">
            <router-link :to="{name: 'customer-company-show', params:{id: customer_company.id}}"> {{ customer_company.name }}</router-link>
        </div>
        <div class="td" style="width:20%">{{ customer_company.alias_name }}</div>
        <div class="td" style="width:15%">{{ customer_company.contact_person_name }}</div>
        <div class="td" style="width:10%"> <Status :customer_company="customer_company" /></div>
        <div class="td" style="width:5%">
            <MenuBarVue :customer_company="customer_company" />
        </div>
    </div>
</template>

<script>
    import MenuBarVue from './MenuBar.vue';
    import Status from './Status.vue'
    export default {
        props: {
            customer_company: {
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

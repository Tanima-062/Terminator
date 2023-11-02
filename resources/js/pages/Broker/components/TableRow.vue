<template>
    <div class="tr">
        <div class="td" style="width:20%">{{ formateDate(broker.created_at) }}</div>
        <!-- <div class="td td-link" style="width:25%">
            <router-link :to="{name: 'customer-company-show', params:{id: broker.customer_company.id}}"> {{ broker.customer_company.name }}</router-link>
        </div> -->
        <div class="td" style="width:30%">{{ broker.name }}</div>
        <div class="td td-link" style="width:30%"><router-link :to="{name: 'broker-show', params:{id: broker.id}}"> {{ broker.contact_person_full_name }}</router-link></div>
        <div class="td" style="width:15%"> <Status :broker="broker" /></div>
        <div class="td" style="width:5%">
            <MenuBarVue :broker="broker" />
        </div>
    </div>
</template>

<script>
    import MenuBarVue from './MenuBar.vue';
    import Status from './Status.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';
    export default {
        props: {
            broker: {
                type: Object,
                required: true
            },
            modelValue: {
                type: Boolean,
                default: false
            }
        },
        components: {
            MenuBarVue,
            Status,
            CheckboxInput
        },
        data(){
            return {
                checked: this.modelValue
            }
        },
        watch: {
            checked(){
                this.$emit('update:modelValue', this.checked)
            },
            modelValue() {
                this.checked = this.modelValue
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>

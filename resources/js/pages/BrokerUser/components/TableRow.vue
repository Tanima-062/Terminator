<template>
    <div class="tr">
        <div class="td" style="width:15%">{{ formateDate(broker_user.created_at) }}</div>
        <div class="td" style="width:15%">
            {{ broker_user.broker.name }}
        </div>
        <div class="td" style="width:20%">{{ translateLanguageName(broker_user.correspondence_language)  }}</div>
        <div class="td td-link" style="width:20%"><router-link :to="{name: 'broker-user-show', params:{id: broker_user.id}}">{{ broker_user.user.full_name }}</router-link></div>
        <div class="td" style="width:9%">{{ $t(broker_user.role) }}</div>
        <div class="td" style="width:16%"> <Status :broker_user="broker_user" /></div>
        <div class="td" style="width:5%">
            <MenuBarVue :broker_user="broker_user" />
        </div>
    </div>
</template>

<script>
    import MenuBarVue from './MenuBar.vue';
    import Status from './Status.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';
    export default {
        props: {
            broker_user: {
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

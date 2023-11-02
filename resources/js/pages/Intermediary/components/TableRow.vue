<template>
    <div class="tr">
        <div class="td" style="width:20%">{{ formateDate(intermediary.created_at) }}</div>
        <div class="td" style="width:25%">{{ translateLanguageName(intermediary.correspondence_language)  }}</div>
        <div class="td td-link" style="width:30%"><router-link :to="{name: 'intermediary-show', params:{id: intermediary.id}}">{{ intermediary.user.full_name }}</router-link></div>
        <div class="td" style="width:20%"> <Status :intermediary="intermediary" /></div>
        <div class="td" style="width:5%">
            <MenuBarVue :intermediary="intermediary" />
        </div>
    </div>
</template>

<script>
    import MenuBarVue from './MenuBar.vue';
    import Status from './Status.vue'
    import CheckboxInput from '@/components/form/CheckboxInput.vue';
    export default {
        props: {
            intermediary: {
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

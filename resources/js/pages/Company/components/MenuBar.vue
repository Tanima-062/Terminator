<template>
    <div class="table__menubar" v-click-away="() => openDropdownMenu = false">
        <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
            <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363"/>
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li> <router-link :to="{name: 'customer-company-show', params:{id: customer_company.id}}">{{ $t('View Details') }}</router-link></li>
            <li> <router-link :to="{name: 'customer-company-edit', params:{id: customer_company.id}}">{{ $t('Edit') }}</router-link></li>
            <li  v-if="status == 'inactive'"> <a @click.prevent="changeStatus('active')"> {{ $t('Activate') }}</a></li>
            <li v-if="status == 'active'"> <a  @click.prevent="changeStatus('inactive')">{{ $t('Deactivate') }}</a> </li>
        </ul>

    </div>
</template>

<script>
    import {trans} from 'laravel-vue-i18n'
    import axios from 'axios';

    export default {
        props: {
            customer_company: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                status: this.customer_company.status,
                openDropdownMenu: false
            }
        },
        methods: {
            changeStatus(status){
                const title = status == 'active' ? 'Activate Customer Company?' : 'Deactivate Customer Company?';
                const description = status == 'active' ? trans('Are you sure you really want to activate the Customer Company “:name”?', {name: this.customer_company.name}) : trans('If you deactivate this Customer Company, all associated users will automatically be deactivated as well. Are you sure you really want to deactivate the Customer Company “:name”?', {name: this.customer_company.name});


                this.$vfm.show('confirmation', {
                    title,
                    description,
                    yesText: 'Yes',
                    yesClick: ()=> this.updateStatus(status),
                })
            },
            async updateStatus(status){
               const res = await axios.put(route('customer-companies.update-status', {customer_company: this.customer_company.id}), {status})

                const description  = status == 'active' ? 'The Customer Company “:name” was successfully activated.': 'The Customer Company “:name”  and all associated users have been successfully deactivated.'

                this.status = status

                this.$vfm.show('success-notification', {
                    description: trans(description, {name: this.customer_company.name}),
                    // duration: 10000
                })
                this.emitter.emit(`update-status-${this.customer_company.id}`, status)
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>

<template>
    <div class="table__menubar" v-click-away="() => openDropdownMenu = false">
        <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
            <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363"/>
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li v-if="!customer_company_admin.user.send_mail && (status=='new' || status=='pending')"><a @click.prevent="sendInvitationEmail()"> {{ $t('Send Invitation') }}</a></li>
            <li> <router-link :to="{name: 'customer-company-admin-show', params:{id: customer_company_admin.id}}">{{ $t('View Details') }}</router-link></li>
            <li> <router-link :to="{name: 'customer-company-admin-edit', params:{id: customer_company_admin.id}}">{{ $t('Edit') }}</router-link></li>
            <li > <a v-if="status == 'inactive'" @click.prevent="changeStatus('active')"> {{ $t('Activate') }}</a></li>
            <li > <a  v-if="status == 'active'" @click.prevent="changeStatus('inactive')">{{ $t('Deactivate') }}</a> </li>
        </ul>

    </div>
</template>

<script>
    import {trans} from 'laravel-vue-i18n'
    import axios from 'axios';

    export default {
        props: {
            customer_company_admin: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                status: this.customer_company_admin.user.status,
                openDropdownMenu: false
            }
        },
        methods: {
            changeStatus(status){
                const title = status == 'active' ? 'Activate Customer Company Admin?' : 'Deactivate Customer Company Admin?';
                const description = status == 'active' ? trans('Are you sure you really want to activate the Customer Company Admin “:name” of the Customer Company “:company”?', {name: this.customer_company_admin.user.name, company: this.customer_company_admin.customer_company.name}) : trans('Are you sure you really want to deactivate the Customer Company Admin “:name” of the Customer Company “:company”?', {name: this.customer_company_admin.user.name, company: this.customer_company_admin.customer_company.name});


                this.$vfm.show('confirmation', {
                    title,
                    description,
                    yesText: 'Yes',
                    yesClick: ()=> this.updateStatus(status),
                })
            },
            async updateStatus(status){
               const res = await axios.put(route('customer-company-admins.update-status', {customer_company_admin: this.customer_company_admin.id}), {status})

                const description  = status == 'active' ? 'The Customer Company Admin “:name” of the Customer Company “:company” was successfully activated.': 'The Customer Company Admin “:name”  of the Customer Company “:company” was successfully deactivated.'

                this.$vfm.show('success-notification', {
                    description: trans(description, {name: this.customer_company_admin.user.name, company: this.customer_company_admin.customer_company.name}),
                })

                this.status = status
                this.emitter.emit(`update-status-${this.customer_company_admin.id}`, status)
            },

            sendInvitationEmail(){
                axios.post(route('customer-company-admins.send-invitation-email',  {user: this.customer_company_admin.user.id}));
                this.$vfm.show('success-notification', {
                    description: trans("The Email invitation was successfully sent."),
                })

                this.openDropdownMenu = false;
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>

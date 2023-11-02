<template>
    <div class="table__menubar" v-click-away="() => openDropdownMenu = false">
        <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
            <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363"/>
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li v-if="!broker_user.send_mail && (status=='new' || status=='pending')"><a @click.prevent="sendInvitationEmail()"> {{ $t('Send Invitation') }}</a></li>
            <li><router-link :to="{name: 'broker-user-show', params:{id: broker_user.id}}">{{ $t('View Details') }}</router-link></li>
            <li> <router-link :to="{name: 'broker-user-edit', params:{id: broker_user.id}}">{{ $t('Edit') }}</router-link></li>
            <li  v-if="status == 'inactive'"> <a @click.prevent="changeStatus('active')"> {{ $t('Activate') }}</a></li>
            <li v-if="status == 'active' || status == 'pending' || status == 'new'"> <a  @click.prevent="changeStatus('inactive')">{{ $t('Deactivate') }}</a> </li>
        </ul>

    </div>
</template>

<script>
    import {trans} from 'laravel-vue-i18n'
    import axios from 'axios';

    export default {
        props: {
            broker_user: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                status: this.broker_user.user.status,
                openDropdownMenu: false
            }
        },
        methods: {
            changeStatus(status){
                const title = status == 'active' ? 'Activate Broker User?' : 'Deactivate Broker User?';
                const description = status == 'active' ? 'Are you sure you really want to activate the Broker User “:name”?' : 'Are you sure you really want to deactivate the Broker User “:name”?';

                this.$vfm.show('confirmation', {
                    title,
                    description: { text: description, replace:  {name: this.broker_user.user.full_name}},
                    yesClick: ()=> this.updateStatus(status),
                })
            },
            async updateStatus(status){
               const res = await axios.put(route('broker-users.update-status', {broker_user: this.broker_user.id}), {status})

                const description  = status == 'active' ? 'The Broker User “:name” was successfully activated.': 'The Broker User “:name” was successfully deactivated.'

                this.status = status

                this.$vfm.show('success-notification', {
                    description: { text: description, replace:  {name: this.broker_user.user.full_name}},
                    // duration: 10000
                })
                this.emitter.emit(`update-status-${this.broker_user.user.id}`, status)
            },
            sendInvitationEmail(){
                axios.post(route('broker_users.send-invitation-email',  {user: this.broker_user.user.id}));
                this.emitter.emit(`update-status-${this.broker_user.user.id}`, 'pending')
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

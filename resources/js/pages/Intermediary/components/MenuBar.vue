<template>
    <div class="table__menubar" v-click-away="() => openDropdownMenu = false">
        <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
            <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363"/>
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li v-if="!intermediary.send_mail && (status=='new' || status=='pending')"><a @click.prevent="sendInvitationEmail()"> {{ $t('Send Invitation') }}</a></li>
            <li><router-link :to="{name: 'intermediary-show', params:{id: intermediary.id}}">{{ $t('View Details') }}</router-link></li>
            <li> <router-link :to="{name: 'intermediary-edit', params:{id: intermediary.id}}">{{ $t('Edit') }}</router-link></li>
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
            intermediary: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                status: this.intermediary.user.status,
                openDropdownMenu: false
            }
        },
        methods: {
            changeStatus(status){
                const title = status == 'active' ? 'Activate intermediary?' : 'Deactivate intermediary?';
                const description = status == 'active' ? 'Are you sure you really want to activate the intermediary “:name”?' : 'Are you sure you really want to deactivate the intermediary “:name”?';

                this.$vfm.show('confirmation', {
                    title,
                    description: { text: description, replace:  {name: this.intermediary.user.full_name}},
                    yesClick: ()=> this.updateStatus(status),
                })
            },
            async updateStatus(status){
               const res = await axios.put(route('intermediaries.update-status', {intermediary: this.intermediary.id}), {status})

                const description  = status == 'active' ? 'The intermediary “:name” was successfully activated.': 'The intermediary “:name” was successfully deactivated.'

                this.status = status

                this.$vfm.show('success-notification', {
                    description: { text: description, replace:  {name: this.intermediary.user.full_name}},
                    // duration: 10000
                })
                this.emitter.emit(`update-status-${this.intermediary.user.id}`, status)
            },
            sendInvitationEmail(){
                axios.post(route('intermediaries.send-invitation-email',  {user: this.intermediary.user.id}));
                this.emitter.emit(`update-status-${this.intermediary.user.id}`, 'pending')
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

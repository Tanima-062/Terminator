<template>
    <div class="table__menubar" v-click-away="() => openDropdownMenu = false">
        <svg class="bar-icon" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openDropdownMenu = !openDropdownMenu">
            <path d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z" fill="#636363"/>
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li> <router-link :to="{name: 'broker-show', params:{id: broker.id}}">{{ $t('View Details') }}</router-link></li>
            <li> <router-link :to="{name: 'broker-edit', params:{id: broker.id}}">{{ $t('Edit') }}</router-link></li>
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
            broker: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                status: this.broker.status,
                openDropdownMenu: false
            }
        },
        methods: {
            changeStatus(status){
                const title = status == 'active' ? 'Activate Broker?' : 'Deactivate Broker?';
                const description = status == 'active' ? trans('Are you sure you really want to activate the Broker “:name”?', {name: this.broker.name}) : trans('If you deactivate this Broker, all associated users will automatically be deactivated as well. Are you sure you really want to deactivate the Broker “:name”?', {name: this.broker.name});


                this.$vfm.show('confirmation', {
                    title,
                    description,
                    yesClick: ()=> this.updateStatus(status),
                })
            },
            async updateStatus(status){
               const res = await axios.put(route('brokers.update-status', {broker: this.broker.id}), {status})

                const description  = status == 'active' ? 'The Broker “:name” was successfully activated.': 'The Broker “:name”  and all associated users have been successfully deactivated.'

                this.status = status

                this.$vfm.show('success-notification', {
                    description: trans(description, {name: this.broker.name}),
                    // duration: 10000
                })
                this.emitter.emit(`update-status-${this.broker.id}`, status)
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>

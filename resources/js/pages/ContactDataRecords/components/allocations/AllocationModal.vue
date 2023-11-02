<template>
    <vue-final-modal
      v-model="show"
      name="allocation-modal"
      classes="flex justify-center items-center"
      content-class="bg-white px-[77px] pt-[48px] pb-[72px] rounded-sm w-[546px]"
      :click-to-close="false"
      :keep-overlay="true"
    >
      <div class="header flex mb-[36px]">
        <div class="left">
            <h2 class="title">
                <template v-if="type == 'lead' ">
                        {{ $t('Allocate Lead_s') }}
                </template>
                <template v-if="type == 'appointment' ">
                        {{ $t('Allocate Appointment_s') }}
                </template>

                <template v-if="type == 'all' ">
                        {{ $t('Allocate Contact Data Record') }}
                </template>
            </h2>
        </div>
        <div class="right ml-auto flex items-center">
            <label for="allocation_count" class="allocation_count_label">Allocate</label>
            <input maxlength="3" id="allocation_count" type="text" class="allocation_count" v-model.number="form.allocation_count" :readonly="selectedCheckBoxCount > 0">
        </div>
      </div>

      <div class="body">
        <form>
            <div class="form__item mb-[30px]">
                <SingleSelect
                    label="Allocate to"
                    placeholder="Allocate to select"
                    :asterisk="true"
                    :options="allocate_to_lists" v-model="form.allocate_to"
                    optionsClass="w-full right-0" class="w-full"
                />
            </div>

            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Leader Head of'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="leader_head_of_users_lists" v-model="form.leader_head_of_user_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>
            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Manager'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="manager_in_users_lists" v-model="form.manager_in_user_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>
            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Quality controller'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="quality_controller_users_lists" v-model="form.quality_controller_user_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>
            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Call agent'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="call_agent_users_lists" v-model="form.call_agent_users_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>

            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Broker'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="broker_lists" v-model="form.broker_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>
            <div class="form__item mb-[30px]" v-if="form.allocate_to == 'Broker User'">
                <SingleSelectWithSearch
                    label="Select User_Broker"
                    placeholder="First and last name of the user"
                    :asterisk="true"
                    :options="broker_users_lists" v-model="form.broker_user_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
                />
            </div>
            <div class="form__item mb-[94px]">
                <SingleSelect
                    label="Campaign"
                    placeholder="Select campaign"
                    :asterisk="false"
                    :options="campaign_lists" v-model="form.campaign_id"
                    optionsClass="w-full right-0" class="w-full"
                />
            </div>
            <div class="form__item flex justify-between gap-6">
                <ButtonGradient @click.prevent="submit(contact_data_records)" class="w-1/2 h-[48px] m-auto" :class="{disabled: isSubmitButtonDisabled}">
                    {{ $t("Allocate") }}
                </ButtonGradient>
                <ButtonWhite
                    @click.prevent="showCancelModal"
                    class="w-1/2 h-[48px] m-auto"
                >
                    {{ $t("Cancel") }}
                </ButtonWhite>
            </div>
        </form>
      </div>

    </vue-final-modal>
  </template>

  <script>
    import ButtonGradient from "@/components/button/Gradient.vue";
    import ButtonWhite from "@/components/button/White.vue";
    import SingleSelect from '@/components/form/SingleSelect.vue'
    import SingleSelectWithSearch from '../SingleSelectWithSearch.vue'

    import TextInput from "@/components/form/TextInput.vue";
    import Form from 'vform'
    import {trans} from 'laravel-vue-i18n'

    import axios from 'axios';
    export default {
        components: {
            ButtonGradient,
            ButtonWhite,
            SingleSelect,
            SingleSelectWithSearch,
            TextInput
        },
        props: {
            contact_data_records: {
                type: Object,
                required: true,
            },
            type: {
                type: String,
                required: true,
            }
        },
        created(){
            this.form.allocation_count = this.selectedCheckBoxCount
            this.getOptionData();
        },
        data(){
            return {
                show: false,

                form: new Form({
                    campaign_id: '',
                    allocate_to: '',
                    broker_id: '',
                    broker_user_id: '',
                    leader_head_of_user_id: '',
                    manager_in_user_id: '',
                    quality_controller_user_id: '',
                    call_agent_users_id: '',
                    allocation_count: 0
                }),
                allocate_to_lists: [],
                // allocate_to: null,

                campaign_lists: [],
                // campaign_id: null,

                broker_lists: [],
                // broker_id: null,

                broker_users_lists: [],
                // broker_user_id: null,

                leader_head_of_users_lists: [],
                // leader_head_of_user_id: null,

                manager_in_users_lists: [],
                // manager_in_user_id: null,

                quality_controller_users_lists: [],
                // quality_controller_user_id: null,

                call_agent_users_lists: [],
                // call_agent_users_id: null,
            }
        },
        computed: {
            isSubmitButtonDisabled(){
                if(!this.form.allocate_to) return true;
                if(this.form.allocation_count < 1 || typeof this.form.allocation_count != 'number') return true;

                if(this.form.allocate_to == 'Leader Head of' && !this.form.leader_head_of_user_id ) return true;
                if(this.form.allocate_to == 'Manager' && !this.form.manager_in_user_id ) return true;
                if(this.form.allocate_to == 'Quality controller' && !this.form.quality_controller_user_id ) return true;
                if(this.form.allocate_to == 'Call agent' && !this.form.call_agent_users_id ) return true;

                if(this.form.allocate_to == 'Broker' && !this.form.broker_id ) return true;
                if(this.form.allocate_to == 'Broker User' && !this.form.broker_user_id ) return true;

                return false;
            },
            selectedCheckBoxCount(){
                return Object.values(this.contact_data_records).filter(item=>item).length;
            }
        },
        methods: {
            async getOptionData(){
                try {
                    const {data} = await axios.get(route('contact-data-records.allocation.get-options-data'));
                    // console.log(data)
                    this.allocate_to_lists = data.allocate_to_lists
                    this.broker_lists = data.broker_lists
                    this.broker_users_lists = data.broker_users_lists
                    this.leader_head_of_users_lists = data.leader_head_of_users_lists
                    this.manager_in_users_lists = data.manager_in_users_lists
                    this.quality_controller_users_lists = data.quality_controller_users_lists
                    this.call_agent_users_lists = data.call_agent_users_lists
                    this.campaign_lists = data.campaign_lists
                } catch (error) {
                    console.log(error);
                }
            },
            submit(){
                this.form.contact_data_records = this.contact_data_records;
                this.form.tab = this.type;
                if(this.selectedCheckBoxCount > 0 ){
                    this.form.type =    'checkbox'
                }else {
                    this.form.type =    'manual_input'
                }

                // this.form.contact_data_records = contact_data_records;

                // console.log(this.contact_data_records)
                // return

                let title = '';
                let description = ''

                if(this.type == 'lead'){
                    title = 'Allocate selected lead_s?';
                    description = 'Are you sure you want to allocate the selected lead(s) according to your selection?'
                }else if(this.type == 'appointment'){
                    title = 'Allocate selected appointment_s?';
                    description = 'Are you sure you want to allocate the selected appointment_s according to your selection?'
                }else if(this.type == 'all'){
                    title = 'Allocate selected contact data records?';
                    description = 'Are you sure you want to allocate the selected contact data records according to your selection?'
                }

                this.$vfm.show("confirm-allocation-modal", {
                    title: title,
                    description: description,
                    yesClick: ()=> {
                        // console.log('yes clicked');
                        // console.log(this.form)
                        // this.$vfm.hide('allocation-modal')
                        this.allocate();
                    }
                });
            },
            showCancelModal(){
                let title = '';
                let description = ''

                if(this.type == 'lead'){
                    title = 'Cancel Lead Distribution?';
                    description = 'Are you sure you really want to cancel the lead distribution?'
                }else if(this.type == 'appointment'){
                    title = 'Cancel Appointment Allocation?';
                    description = 'Are you sure you really want to cancel the appointment allocation?'
                }else if(this.type == 'all'){
                    title = 'Cancel Contact Data Record Allocation?';
                    description = 'Are you sure you really want to cancel the contact data record allocation?'
                }

                this.$vfm.show('cancel-allocation-modal', {
                        title:  title,
                        description: description,
                    })
            },
            allocate(){
                this.form.post(route('contact-data-records.allocation.store'))
                    .then(res=>{
                        this.form.reset();

                        let description = ''

                        if(this.type == 'lead'){
                            description = 'The selected lead(s) were successfully allocated.'
                        }else if(this.type == 'appointment'){
                            description = 'The selected appointment_s were successfully allocated.'
                        }else if(this.type == 'all'){
                            description = 'The selected contact data records were successfully allocated.'
                        }

                        this.$vfm.show('success-notification', {
                            description: description,
                            // duration: 10000
                        })

                        this.show = false
                        this.$vfm.hide('allocation-modal');

                       setTimeout(()=> {
                        location.reload();
                       }, 3000)
                    })
                    .catch(err=> {
                        console.log(err);
                    })
            }
        }
    }
  </script>

  <style lang="scss" scoped>
    .title {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 600;
        font-size: 20px;
        line-height: 24px;
        color: #AB326F;

    }

    .disabled {
        background: #BBBBBB;
        box-shadow: 1px 3px 15px rgba(0, 0, 0, 0.25);
        border-radius: 10px;
        font-family: 'Inter';
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        color: #FFFFFF;
        pointer-events: none;
    }

    .allocation_count_label {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: 0.02em;
        color: #AB326F;
        margin-right: 5px;
    }

    .allocation_count {
        border: 1px solid #E6DEE5;
        border-radius: 5px;
        width: 60px;
        height: 25px;
        font-family: 'Inter';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 16px;
        letter-spacing: 0.04em;
        color: #636363;
        padding: 8px 10px;
        outline: none;

        &:focus {
            outline: none;
        }
    }
  </style>

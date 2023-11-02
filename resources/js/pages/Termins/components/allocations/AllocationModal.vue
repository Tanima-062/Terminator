<template>
    <vue-final-modal
      v-model="show"
      name="allocation-modal"
      classes="flex justify-center items-center"
      content-class="bg-white px-[77px] pt-[48px] pb-[94px] rounded-sm w-[546px]"
      :click-to-close="false"
      :keep-overlay="true"
    >
      <div class="header flex mb-[36px]">
        <div class="left">
            <h2 class="title">
                {{ $t('Allocate Appointment_s') }}
            </h2>
        </div>
      </div>

      <div class="body">
        <form>
            <div class="form__item mb-[112px]">
                <SingleSelectWithSearch
                    label="Allocate to"
                    placeholder="Select allocate to"
                    :asterisk="true"
                    :options="intermediary_users_lists" v-model="form.intermediary_user_id"
                    optionsClass="w-full right-0" class="w-full"
                    :searchables="['label']"
                    :searchable="true"
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
                    intermediary_user_id: null,
                }),
                intermediary_users_lists: [],
            }
        },
        computed: {
            isSubmitButtonDisabled(){
               return !this.form.intermediary_user_id;
            },

        },
        methods: {
            async getOptionData(){
                try {
                    const {data} = await axios.get(route('termins.allocation.get-options-data'));
                    // console.log(data)
                    this.intermediary_users_lists = data.intermediary_users_lists
                } catch (error) {
                    console.log(error);
                }
            },
            submit(){
                this.form.contact_data_records = this.contact_data_records;

                const title = 'Allocate selected appointment_s?';
                const description = 'Are you sure you want to allocate the selected appointment_s according to your selection?'

                this.$vfm.show("confirm-allocation-modal", {
                    title: title,
                    description: description,
                    yesClick: ()=> {
                        this.allocate();
                    }
                });
            },
            showCancelModal(){
                const title = 'Cancel Appointment Allocation?';
                const description = 'Are you sure you really want to cancel the appointment allocation?'

                this.$vfm.show('cancel-allocation-modal', {
                        title:  title,
                        description: description,
                    })
            },
            allocate(){
                this.form.post(route('termins.allocation.store'))
                    .then(res=>{
                        this.form.reset();

                        const description = 'The selected appointment_s were successfully allocated.'

                        this.$vfm.show('success-notification', {
                            description: description,
                            // duration: 10000
                        })

                        this.show = false
                        this.$vfm.hide('allocation-modal');
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

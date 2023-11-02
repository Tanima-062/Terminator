<template>
    <div class="relative">
        <button
          class="w-full h-12 block  text-[16px] leading-[19px] rounded-sm  font-inter text-white text-center px-[30px] py-[15px] font-medium "
          type="button"
          :class="[
                {'bg-gradient-to-r from-btnGradient2  to-btnGradient1 hover:pointer': !disabled, 'bg-[#BBBBBB] cursor-default pointer-events-none': disabled},
                {'open': open},
                 $attrs.class
            ]"
        >
          <slot />
        </button>
    </div>
  </template>

  <script>
  import LeadAgainModal from './LeadAgainModal.vue'
  export default {
      props: {
          disabled: {
              type: Boolean,
              default: false
          },
      },
      data(){
        return {
            open: false,
        }
      },
      methods: {
        confirm(){
            // this.$vfm.show('lead-again-modal', {
            //         title: "Set as Lead again?",
            //         description: "If you set a contact record to “New lead”, it will be placed at the start of the workflow (New status). In addition, the data should then be checked and updated if necessary, and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to set the selected contact data records to “Lead again”?",
            //         yesClick: ()=> this.setLeadAgain(),
            //     })

            const options = {
                component: LeadAgainModal,
                bind: {
                    // contact_data_records: selectedRows.value,
                    // type: 'lead',
                    // type: 'Allocate Lead_s'
                    title: "Set as Lead again?",
                    description: "If you set a contact record to “New lead”, it will be placed at the start of the workflow (New status). In addition, the data should then be checked and updated if necessary, and then the data confirmed by selecting the corresponding checkbox. Are you sure that you really want to set the selected contact data records to “Lead again”?",
                    yesClick: this.setLeadAgain
                },
            }
            this.$vfm.show(options)

        },
        setLeadAgain(){
            // console.log('set lead again');
        }
      }
  }
  </script>


<style lang="scss" scoped>
    .open {
        background: linear-gradient(180deg, #3F8AA7 -1.04%, #76B5CE 6.54%, #67A7C0 13.36%, #5A9FB9 19.17%, #5398B4 25.44%, #3D89A6 32.79%, #3181A0 38.36%, #3281A0 38.55%, #297C9C 108.33%);
        border: 1px solid #297C9C;
        box-shadow: 0px -1px 12px rgba(0, 0, 0, 0.31);
        border-radius: 10px 10px 0px 0px;

        font-family: 'Inter';
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;

        color: #FFFFFF;

    }

    .dropdown__menu {
        background: #FFFFFF;
        border: 1px solid #E6DEE5;
        border-radius: 10px 0px 10px 10px;
        padding-top: 15px;
        padding-bottom: 15px;

        .dropdown--item {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 13px;
            line-height: 16px;
            color: #636363;
            padding: 5px 20px;
            cursor: pointer;

            &:hover {
                background: #A13575;
                color: #FFFFFF;
            }
        }
    }
</style>

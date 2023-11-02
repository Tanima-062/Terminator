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
          @click.prevent="openDropdown"
        >
        {{ $t('Import') }} {{  campaign_id }}
        </button>
        <ul class="dropdown__menu absolute right-0 w-[210px]" v-if="open" v-click-away="()=>open = false">
            <li class="dropdown--item" @click="selectCampaign(campaign.id)" v-for="campaign in campaigns" :key="campaign.id">{{ campaign.name }}</li>
        </ul>
        <!-- <input type="file" ref="file_input"  style="display: none;" @change="changeFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  /> -->
        <input type="file" ref="file_input"  style="display: none;" @change="changeFile" accept=".csv"  />
    </div>
  </template>

  <script>
  export default {
      props: {
          disabled: {
              type: Boolean,
              default: false
          },
        //   tab: {
        //     type: String,
        //     required: true
        //   },
      },
      data(){
        return {
            open: false,
            campaign_id: null,
            campaigns: [],
            file: null
        }
      },
      created(){
        this.getCampaigns()
      },
      methods: {
        getCampaigns() {
            axios.get(route('campaign.index'))
                .then(res=> {
                    this.campaigns = res.data
                }).catch(err=> {
                    console.log(err)
                })
        },
        openDropdown() {
            if(this.campaigns.length > 1){
                this.open = !this.open
            }
            else {
                this.campaign_id = this.campaigns[0]['id'];
                this.$refs.file_input.click();
            }
        },
        selectCampaign(campaign_id) {
            this.campaign_id = campaign_id;
            this.$refs.file_input.click();
        },
        async changeFile(e) {
            try {
                const file = e.target.files[0];
                const formData = new FormData();
                formData.append('campaign_id', this.campaign_id);
                formData.append('import_file', file);
                const res = await  axios.post(route('contact-data-records.imports'), formData)
                // console.log(res)
                location.reload()
            } catch (error) {
                // console.log(error)
                alert('Invalid data');
            } finally {
                this.$refs.file_input.value = null
            }

        },

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

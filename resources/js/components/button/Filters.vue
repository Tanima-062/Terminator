<template>
    <div
      class="filter__button relative"
      :class="{'filter__button--open': openFilterMenu}"
    >
        <button
            class="filter__button--close cursor-pointer  flex justify-center items-center  w-full font-inter text-white text-center rounded-[10px] px-[30px] py-[15px] font-medium"
             @click="openFilterMenu = !openFilterMenu"
        >
            <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.2023 0H0.797801C0.0899769 0 -0.267189 0.909351 0.234344 1.44039L6.375 7.94324V15.1875C6.375 15.4628 6.50186 15.7208 6.7149 15.8787L9.37115 17.8468C9.89519 18.2352 10.625 17.8415 10.625 17.1555V7.94324L16.7658 1.44039C17.2663 0.910406 16.9116 0 16.2023 0Z" fill="white"/>
            </svg>
            {{  $t('Filter') }}
        </button>

        <div class="filter__menu absolute" v-if="openFilterMenu" :style="{width: width}">
            <div class="filter__menu--header mb-8 flex justify-end ">
                <slot name="close">
                    <svg class="cursor-pointer" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" @click="openFilterMenu = false">
                        <path d="M13 1L1 13M1 1L13 13" stroke="#AB326F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </slot>
            </div>
            <div class="filter__menu--body">
                <slot :key="componentKey"></slot>
            </div>
            <div class="filter__menu--footer flex w-full gap-3 mt-5">
                <slot name="footer">
                    <button
                        class="filter__apply--button font-inter text-white text-center rounded-[10px] px-[30px] py-[12px] font-medium  w-1/2"
                        @click="apply"
                    >
                        {{ $t('Apply') }}
                    </button>
                    <button
                        :class="`filter__reset--button font-inter text-white text-center rounded-[10px] ${reset=='Reset' ? 'px[30px]' : 'px[15px]'} py-[12px] font-medium  w-1/2`"
                        @click="reset"
                    >
                        <div>{{ $t('Reset') }}</div>
                    </button>
                </slot>
            </div>
        </div>
    </div>
  </template>

  <script>
  import { reactive, computed, ref } from "@vue/reactivity";
    import { trans } from "laravel-vue-i18n";
    const reset = ref(trans('Reset'))
    export default {
        props: {
            // route: {
            //     type: [Object, String],
            //     required: true,
            // },
            width: {
                type: String,
                default: '327px'
            }
        },
        data(){
            return {
                openFilterMenu: false,
                componentKey: 0
            }
        },
        methods: {
            apply(){
                this.$emit('apply')
                this.openFilterMenu = false;
            },
            reset(){
                // this.componentKey++;
                this.$emit('reset')
                this.openFilterMenu = false;
            }
        }
    }
  </script>

  <style lang="scss" scoped>
      .filter__button{
          background: linear-gradient(262.9deg, #C52E62 -44.07%, #8B387F 155.41%);
          border-radius: 10px;
          font-size: 16px;
          line-height: 19px;

          svg {
            margin-right: 10px;
          }
      }

      .filter__button--open {
        background: linear-gradient(180deg, #3F8AA7 -7.29%, #76B5CE 0.72%, #67A7C0 7.93%, #5A9FB9 14.08%, #5398B4 20.71%, #3D89A6 28.48%, #3181A0 34.37%, #3281A0 34.56%, #297C9C 108.33%);
        border-radius: 10px 10px 0px 0px;
      }

      .filter__menu {
        box-sizing: border-box;
        position: absolute;
        z-index: 9999;
        right: 0;
        top: 48px;
        width: 327px;

        background: #FFFFFF;
        border: 1px solid #E6DEE5;
        box-shadow: 0px 2px 20px rgba(95, 91, 96, 0.25);
        border-radius: 10px 0px 10px 10px;

        padding-top: 22px;
        padding-bottom: 34px;
        padding-left: 27px;
        padding-right: 20px;
      }

      .filter__apply--button  {
        background: linear-gradient(262.9deg, #C52E62 -44.07%, #8B387F 155.41%);
          box-shadow: 1px 3px 15px rgba(0, 0, 0, 0.25);
          border-radius: 10px;
          font-size: 16px;
          line-height: 19px;
      }
      .filter__reset--button  {
        background: #FFFFFF;
        border: 1.5px solid #8B387F;
        border-radius: 10px;
        font-family: 'Inter';
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;

        color: #636363;
      }
  </style>

<template>
    <div class="content px-6">
         <div class="rounded-[15px] bg-white px-[30px] py-[40px] overflow-y-auto pr-[127px] shadow-[1px_4px_28px_rgba(95,_76,_92,_0.18)]">
             <div class="flex items-center hover:cursor-pointer" @click="()=> $router.back()">
                 <svg
                     width="17"
                     height="12"
                     viewBox="0 0 17 12"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                 >
                     <path
                     d="M1 6L16 6M1 6L6.625 1M1 6L6.625 11"
                     stroke="#ADB5BD"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     />
                 </svg>
                 <p class="ml-[10px] text-[16px] font-inter text-[#636363]">{{ $t('Back') }}</p>
             </div>
             <div class="mt-[33px] mb-[40px] xs:mb-[20px]">
                 <h1 class="text-formHeading text-title font-poppins mb-[60px]">
                     {{$t("Broker User Details")}}
                 </h1>
             </div>
             <template v-if="brokerUser">
             <div class="flex">
                 <div class="w-1/3">
                     <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                         {{ $t("Creation Date") }}
                     </h2>
                     <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ formateDate(brokerUser.created_at) }}</p>
                 </div>

                 <div class="w-1/3">
                     <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                         {{ $t("Broker User ID") }}
                     </h2>
                     <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ brokerUser.prefix_id }}</p>
                 </div>
                 <div class="w-1/3">
                     <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                         {{ $t("Broker") }}
                     </h2>
                     <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ brokerUser.broker?.name }}</p>
                 </div>
             </div>
             <div class="spacer mt-[35px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Role") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ $t(brokerUser.role) }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Correspondence Language") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ translateLanguageName(brokerUser.correspondence_language)  }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("System Language") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ $t('German') }}
                    </p>
                </div>
            </div>

                <!-- Third Row -->
            <div class="spacer mt-[35px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Salutation") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ $t(brokerUser.salutation) }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("First Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ brokerUser?.user?.first_name }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Last Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ brokerUser.user.last_name }}
                    </p>
                </div>
            </div>
            <div class="spacer mt-[35px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Email Address") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ brokerUser?.user?.email }}
                    </p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Phone Number") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                    {{ brokerUser.full_phone_number }}
                    </p>
                </div>
            </div>
             <div class="spacer mt-[80px] col-span-3"></div>


             <div class="relative">
                 <router-link :to="{name: 'broker-user-edit', params:{id: $route.params.id}}">
                     <ButtonGradient
                         class="w-[187px]"
                         type="submit"
                     >
                     <PencilIcon class="absolute" style="left:3%"/>
                        <span v-if="edit=='Bearbeiten'" class="mt-auto mb-auto ml-[45px]">{{$t('Edit')}}</span>
                        <span v-else class="m-auto">{{$t('Edit')}}</span>
                     </ButtonGradient>
                 </router-link>
             </div>
            </template>
         </div>
     </div>
 </template>

 <script setup>

 import { reactive, ref, computed } from "@vue/reactivity"
 import ButtonGradient from "@/components/button/Gradient.vue";
 import PencilIcon from "@/components/icons/PencilIcon.vue";
 import {useRoute, useRouter} from 'vue-router'
 import axios from 'axios'
 import { useGetCountryName } from "@/composables/translation.js";
 import { trans } from "laravel-vue-i18n";

 const vueRoute = useRoute();
 const router = useRouter();

 const brokerUser = ref();
 const edit = ref(trans('Edit'))

 const getData = async ()=>{
     try {
         const {data} = await axios.get(route('broker-users.show', {broker_user : vueRoute.params.id}))
         console.log(data);
         brokerUser.value = data
     } catch (error) {
        if(error.response.status == 404){
        router.push({name: '404'})
        }
        if(error.response.status == 403){
            router.push({ name: '403' })
        }
     }
 }

 getData();
 </script>

 <style lang="scss" scoped>

 </style>



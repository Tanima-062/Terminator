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
                    {{$t("Customer Company Admin Details")}}
                </h1>
            </div>
            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Creation Date") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ formateDate(customer_company_admin.created_at) }}</p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Admin ID") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin.prefix_id }}</p>
                </div>
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Customer Company") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]" style="color:#13A3E5;" v-if="customer_company_admin.customer_company_id">
                        <router-link
                            :to="{name: 'customer-company-show', params:{id: customer_company_admin.customer_company_id}}">
                            {{ customer_company_admin?.customer_company?.name }}
                        </router-link>
                    </p>
                </div>
            </div>
            <div class="spacer mt-[35px] col-span-3"></div>
            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Language") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin.user?.language?.name }}</p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("First Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin?.user?.first_name }}</p>
                </div>
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Last Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin?.user?.last_name }}</p>
                </div>
            </div>
            <div class="spacer mt-[35px] col-span-3"></div>
            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Email Address") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin.user?.email }}</p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Phone Number") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">{{ customer_company_admin.full_phone_number }}</p>
                </div>
            </div>
            <div class="spacer mt-[80px] col-span-3"></div>


            <div class="relative">
                <router-link :to="{name: 'customer-company-admin-edit', params:{id: $route.params.id}}">
                    <ButtonGradient
                        class="w-[187px]"
                        type="submit"
                    >
                    <PencilIcon class="absolute" style="left:3%"/> <span class="m-auto">{{ $t('Edit') }}</span>
                    </ButtonGradient>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from "@vue/reactivity"
import ButtonGradient from "@/components/button/Gradient.vue";
import PencilIcon from "@/components/icons/PencilIcon.vue";
import {useRoute, useRouter} from 'vue-router'
import axios from 'axios'

const vueRoute = useRoute();
const router = useRouter();

const customer_company_admin = ref({});

const getData = async ()=>{
    try {
        const{data} = await axios.get(route('customer-company-admins.show', {customer_company_admin : vueRoute.params.id}))
        customer_company_admin.value = data
    } catch (error) {
        if(error.response.status == 404) {
            router.push({ name: '404' })
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

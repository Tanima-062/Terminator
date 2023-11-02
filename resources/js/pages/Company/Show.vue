<template>
    <div class="content px-6">


        <div
            class=" bg-white shadow-xl shadow-#1f2937-800/25 px-[42px] py-[43px] overflow-y-auto pr-[127px] rounded-[15px]">
            <Back class="mb-[33px]" />

            <div class="mb-[40px] xs:mb-[20px]">
                <h1 class="text-formHeading text-title font-poppins mb-[60px]">
                    Customer Company Details
                </h1>
            </div>

            <!-- First Row -->
            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Creation Date") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        v-date-format="customer_company.created_at"></p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Company ID") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.prefix_id }}
                    </p>
                </div>
            </div>

            <div class="spacer mt-[25px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Company Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.name }}
                    </p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Company Alias Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.alias_name }}
                    </p>
                </div>
            </div>

            <!-- Second Row -->
            <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[24px]">
                {{ $t("Company Address") }}
            </h1>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Street Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.street_name }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Street Number") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.street_number }}
                    </p>
                </div>

                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Zip Code") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.zip_code }}
                    </p>
                </div>
            </div>

            <!-- Third Row -->
            <div class="spacer mt-[35px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("City") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.city }}
                    </p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Country") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ useGetCountryName(customer_company.country_iso_code) }}
                    </p>
                </div>
            </div>

            <div class="spacer mt-[35px] col-span-3"></div>

            <!-- Fourth Row -->
            <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[34px]">
                {{ $t("Contact Person") }}
            </h1>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("First Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.contact_person_first_name }}
                    </p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Last Name") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.contact_person_last_name }}
                    </p>
                </div>
            </div>

            <div class="spacer mt-[25px] col-span-3"></div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Email Address") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.contact_person_email }}
                    </p>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Phone Number") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.contact_person_full_phone_number }}
                    </p>
                </div>
            </div>

            <!-- Fifth Row -->
            <h1 class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[35px]">
                {{ $t("Company Specific Configurations") }}
            </h1>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Device Authentication required?") }}
                    </h2>
                    <div class="text-value text-4 font-[400px] font-inter leading-[19px] flex">
                        <div v-if="customer_company.device_authentication_required == 1">
                            <div class="flex">
                                <div class="mt-1">
                                    <span>
                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="0.5" width="14" height="14" rx="7" stroke="#AB326F" />
                                            <circle cx="7.5" cy="7.5" r="4.5" fill="#AB326F" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-1 mt-[.50px]">Yes</div>
                            </div>
                            <div class="flex mt-[15px]">
                                <h2 class="label text-formLabel font-inter text-input mb-[10px] whitespace-nowrap">
                                    {{ $t("User Roles:") }}
                                </h2>
                                <div class="ml-1 mt-[2px]">{{ customer_company.user_roles }}</div>
                            </div>
                        </div>
                        <div class="flex" v-if="customer_company.device_authentication_required == 0">
                            <div class="mt-1">
                                <span>
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.5" y="0.5" width="14" height="14" rx="7" stroke="#AB326F" />
                                        <circle cx="7.5" cy="7.5" r="4.5" fill="#AB326F" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-1 mt-[.50px]">No</div>
                        </div>
                    </div>
                </div>

                <div class="w-2/3">
                    <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                        {{ $t("Auto Logout Time for all Users after") }}
                    </h2>
                    <p class="text-value text-4 font-[400px] font-inter leading-[19px]">
                        {{ customer_company.auto_logout_time }}
                    </p>
                </div>
            </div>

            <div class="spacer mt-[35px] col-span-3"></div>

            <div class="w-1/3">
                <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Organizational elements required?") }}
                </h2>
                <div class="text-value text-4 font-[400px] font-inter leading-[19px] flex">
                    <div class="flex" v-if="customer_company.hierarchy_elements_required == 1">
                        <div class="mt-1">
                            <span>
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.5" width="14" height="14" rx="7" stroke="#AB326F" />
                                    <circle cx="7.5" cy="7.5" r="4.5" fill="#AB326F" />
                                </svg>
                            </span>
                        </div>
                        <div class="ml-1 mt-[.50px]">Yes</div>
                    </div>
                    <div class="flex" v-if="customer_company.hierarchy_elements_required == 0">
                        <div class="mt-1">
                            <span>
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.5" width="14" height="14" rx="7" stroke="#AB326F" />
                                    <circle cx="7.5" cy="7.5" r="4.5" fill="#AB326F" />
                                </svg>
                            </span>
                        </div>
                        <div class="ml-1 mt-[.50px]">No</div>
                    </div>
                </div>
            </div>

            <div class="spacer mt-[25px] col-span-3"></div>

            <HierarchyElementWithControl
                v-if="customer_company.hierarchy_elements_required && customer_company.hierarchy_elements?.length"
                v-model="customer_company.hierarchy_elements" :company_name="customer_company.name" :not_editable="true"/>

            <div class="spacer mt-[80px] col-span-3"></div>

            <div class="relative">
                <router-link :to="{ name: 'customer-company-edit', params: { id: $route.params.id } }">
                    <ButtonGradient class="w-[187px]" type="submit"
                        @click="$router.push({ name: 'customer-company-edit', id: customer_company.id })">
                        <PencilIcon class="absolute" style="left: 3%" />
                        <span class="m-auto">{{ $t("Edit") }}</span>
                    </ButtonGradient>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import ButtonGradient from "@/components/button/Gradient.vue";
import PencilIcon from "@/components/icons/PencilIcon.vue";
import { ref, computed } from "@vue/reactivity";
import { trans } from "laravel-vue-i18n";
import { useGetCountryName } from "@/composables/translation.js";
import HierarchyElementWithControl from "./components/HierarchyElementWithControl.vue";
import Back from "@/components/form/Back.vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const vueRoute = useRoute();
const router = useRouter();

const customer_company = ref([]);

const hierarchy_levels = computed(() => {
    let levels = [1, 2, 3];
    levels = levels.map((i) => ({ value: i, label: i }));
    levels.unshift({ label: trans("None"), value: null });
    return levels;
});

const getData = async () => {

    try {
        const { data } = await axios.get(
            route("customer-companies.show", { customer_company: vueRoute.params.id })
        );
        customer_company.value = data;
    } catch (error) {
        if(error.response.status == 404) {
            router.push({ name: '404' })
        }
        if(error.response.status == 403) {
            router.push({ name: '403' })
        }
    }
};

getData();
</script>

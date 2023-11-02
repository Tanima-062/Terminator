<template>
    <vue-final-modal
        classes="flex justify-center items-center"
        content-class="bg-white px-[77px] pt-12 pb-[54px] rounded-sm w-[621px]"
        :keep-overlay="true"
        :click-to-close="false"
        v-slot="{ close }"
    >
        <CrossIcon class="ml-auto cursor-pointer" @click="close" />

        <h1 class="text-[20px] mb-[42px] text-heading leading-6 font-semibold">
            {{ title }}
        </h1>
        <div class="organization-competences flex flex-col col-span-3 mt-5">
            <div class="flex w-full min-h-[31px]">
                <div
                    class="text-[16px] leading-[19px] text-[#292929] w-[33%] font-semibold font-inter"
                    style="color: #555555"
                >
                    {{ $t("Status") }}
                </div>
                <div
                    class="text-[16px] leading-[19px] text-[#292929] w-[43%] font-semibold font-inter"
                    style="color: #555555"
                >
                    {{ $t("Set on") }}
                </div>
                <div
                    class="text-[16px] leading-[19px] text-[#292929] w-[23%] font-semibold font-inter"
                    style="color: #555555"
                >
                    {{ $t("Set by") }}
                </div>
            </div>
            <div class="competences">
                <div
                    class="flex justify-center min-h-[45px]"
                    v-for="(competence, index) in competence_log"
                    :key="index"
                >
                    <div class="td w-[33%]">
                        {{
                            competence.status == "confirmed"
                                ? $t("Confirmed")
                                : $t("Not confirmed")
                        }}
                    </div>
                    <div class="td w-[43%]">
                        <span
                            v-date-format:datetime="competence.created_at"
                        ></span>
                    </div>
                    <div class="td w-[23%]">
                        <router-link
                            @click="close"
                            class="text-[#13A3E5]"
                            :to="{
                                name: 'customer-company-admin-show',
                                params: {
                                    id: competence.user.customer_company_admin
                                        .id,
                                },
                            }"
                        >
                            {{ competence.user.full_name }}
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </vue-final-modal>
</template>

<script setup>
import CrossIcon from "@/components/icons/Cross.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    competence_log: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
            {{ $t("Add New Customer Company Admin") }}
        </h1>

        <form
            class="form grid grid-cols-3 gap-x-[220px]"
            @submit.prevent="submit"
        >
            <!-- First Row -->
            <div class="flex flex-col">
                <h2
                    class="label text-formLabel font-inter text-input mb-[10px]"
                >
                    {{ $t("Admin ID") }}*
                </h2>
                <p
                    class="text-value text-4 font-semibold font-inter leading-[19px]"
                >
                    {{ prefix_id }}
                </p>
            </div>

            <CompanySelect
                label="Customer Company"
                placeholder="Search Customer Company"
                asterisk="true"
                :options="customerCompanies"
                @country-iso="update_phone_iso"
                :searchables="['label']"
                :searchable="true"
                v-model="form.customer_company_id"
                optionsClass="w-full right-0"
                :error="v$.customer_company_id.$errors.length > 0"
            />

            <SingleSelect
                label="Language"
                placeholder="Select Language"
                asterisk="true"
                :options="languages"
                v-model="form.language_id"
                optionsClass="w-full right-0"
                :error="v$.language_id.$errors.length > 0"
            />

            <!-- Second Row -->
            <div class="spacer col-span-3 mt-[35px]"></div>
            <TextInput
                label="First Name"
                placeholder="Enter first name"
                v-model="form.first_name"
                :asterisk="true"
                :error="v$.first_name.$errors[0]?.$message"
                @input="
                    () => {
                        v$.first_name.$touch();
                    }
                "
            />

            <TextInput
                label="Last Name"
                placeholder="Enter last name"
                v-model="form.last_name"
                :asterisk="true"
                :error="v$.last_name.$errors[0]?.$message"
                @input="
                    () => {
                        v$.last_name.$touch();
                    }
                "
            />

            <!-- Third Row -->
            <div class="spacer col-span-3 mt-[35px]"></div>
            {{ form.errors.errors.email }}

            <TextInput
                label="Email Address"
                :placeholder="'Enter Email Address'"
                :asterisk="true"
                v-model="form.email"
                :error="v$.email.$errors[0]?.$message"
                @input="
                    () => {
                        v$.email.$touch();
                    }
                "
            />

            <PhoneNumberInput
                label="Phone Number"
                v-model:country_code="form.phone_iso_code"
                v-model:phone_number="form.phone_number"
            />

            <div class="spacer col-span-3 mt-[85px]"></div>
            <div
                class="logout-time-input flex items-center col-span-2 gap-[10px]"
            >
                <div class="wrapper w-[6%]">
                    <ToggleSwitch v-model="form.send_mail" />
                </div>
                <span class="text-4 text-input leading-[19px]">{{
                    $t("Send email invitation to Admin")
                }}</span>
            </div>

            <div class="spacer mt-[30px] col-span-3"></div>
            <div class="flex col-span-3 gap-[18px]">
                <ButtonGradient
                    class="w-[20%]"
                    type="submit"
                    :disabled="form.busy"
                >
                    {{ $t("Save") }}
                </ButtonGradient>

                <ButtonWhite
                    class="w-[20%]"
                    type="button"
                    @click="
                        $vfm.show('redirect-modal', {
                            title: 'Cancel Customer Company Admin Registration?',
                            description: `When you abort, all data is discarded. Are you sure you really want to cancel this customer company admin registration?`,
                        })
                    "
                >
                    {{ $t("Cancel") }}
                </ButtonWhite>
            </div>
            <div class="spacer mt-[240px]"></div>
        </form>
    </div>
</template>

<script setup>
import ButtonGradient from "@/components/button/Gradient.vue";
import ButtonWhite from "@/components/button/White.vue";
import TextInput from "@/components/form/TextInput.vue";
import Form from "vform";
import { reactive, ref, computed } from "@vue/reactivity";
import { inject, onBeforeMount } from "@vue/runtime-core";
import SingleSelect from "@/components/form/SingleSelect.vue";
import CompanySelect from "./components/CompanySelect.vue";
import { trans } from "laravel-vue-i18n";
import { useVuelidate } from "@vuelidate/core";
import { required, email, maxLength, helpers } from "@vuelidate/validators";
import PhoneNumberInput from "../../components/form/PhoneNumberInput.vue";
import ToggleSwitch from "@/components/form/ToggleSwitch.vue";
import { notificationShowStore } from "@/store/notification";
import { useRouter } from "vue-router";
import { fromPairs } from "lodash";

import {usePermissionStore} from '@/composables/permission'

let customerCompanies = ref([]);
let prefix_id = ref("");
const router = useRouter();
const permission = usePermissionStore();


const languages = [{ value: 1, label: "German" }];

const $vfm = inject("$vfm");

const form = reactive(
    new Form({
        customer_company_id: "",
        first_name: "",
        last_name: "",
        email: "",
        language_id: 1,
        phone_iso_code: "",
        phone_number: "",
        send_mail: false,
    })
);

const rules = {
    first_name: {
        required: helpers.withMessage("", required),
        maxLength: helpers.withMessage(
            trans("Maximum :length characters possible", { length: 30 }),
            maxLength(30)
        ),
    },
    last_name: {
        required: helpers.withMessage("", required),
        maxLength: helpers.withMessage(
            trans("Maximum :length characters possible", { length: 30 }),
            maxLength(30)
        ),
    },
    language_id: {
        required: helpers.withMessage("", required),
    },
    customer_company_id: {
        required: helpers.withMessage("", required),
    },
    email: {
        required: helpers.withMessage("", required),
        email: helpers.withMessage(trans("Invalid Email format"), email),
    },
};

onBeforeMount(() => {
    getNextPrefixId("customer_company_admin");
});

const v$ = useVuelidate(rules, form);

const getCompanyData = async () => {
    try {
        const { data } = await axios.get(route("customer-companies.get-list"));
        customerCompanies.value = data;
    } catch (error) {
        console.log(error);
    }
};

const getNextPrefixId = async (type) => {
    const { data } = await axios.get(
        route("customer-company-admin.next-prefix-id", {
            _query: { type: type },
        })
    );
    prefix_id.value = data;
};

const submit = async () => {
    v$.value.$touch();
    if (v$.value.$invalid) {
        $vfm.show("field-missing");
        return;
    }

    try {
        const { data } = await form.post(
            route("customer-company-admins.store")
        );
        const notificationStore = notificationShowStore();
        notificationStore.success(trans(data.message), trans("Notification"));
        router.push({ name: "customer-company-admin" });
    } catch (error) {
        if (error.response.data.errors.email_unique) {
            notification(
                error.response.data.errors.email_unique[0],
                "Email can not be used"
            );
        } else if (error.response.data.errors.email_unique_same_company) {
            notification(
                error.response.data.errors.email_unique_same_company[0],
                "Email already exists"
            );
        } else {
            $vfm.show("field-missing");
        }
    }
};

let notification = (message, title) => {
    $vfm.show("email-exist-alert", {
        title: title,
        description: message,
    });
};

const update_phone_iso = (countryIso) => {
    if (!form.phone_iso_code || !form.phone_number)
        form.phone_iso_code = countryIso;
};

getCompanyData();


if( !permission.hasPermission('customer-company-admin:edit')){
    // console.log('has access')
    router.push({ name: '403' })
}
</script>

<style scoped></style>

<template>
    <div
        class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
        <Back
            class="mb-[33px]"
            :show_modal="!useIsEqual(form.data(), form.originalData)"
        />
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
            {{ $t("Edit Customer Company Admin") }}
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
                    {{ $t("Creation Date") }}*
                </h2>
                <p
                    class="text-value text-4 font-semibold font-inter leading-[19px]"
                >
                    {{ creationDate }}
                </p>
            </div>
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
                :searchables="['label']"
                :searchable="true"
                v-model="form.customer_company_id"
                optionsClass="w-full right-0"
                :error="v$.customer_company_id.$errors.length > 0"
            />

            <!-- Second Row -->
            <div class="spacer col-span-3 mt-[35px]"></div>

            <SingleSelect
                label="Language"
                placeholder="Select Language"
                asterisk="true"
                :options="languages"
                v-model="form.language_id"
                optionsClass="w-full right-0"
                :error="v$.language_id.$errors.length > 0"
            />

            <TextInput
                label="First Name"
                :placeholder="'Enter First Name'"
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
                :placeholder="'Enter Last Name'"
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
            <TextInput
                label="Email Address"
                :placeholder="'Enter Email Address'"
                :asterisk="true"
                v-model="form.email"
                :disabled="true"
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

            <div class="spacer mt-[100px] col-span-3"></div>
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
                            title: 'Discard changes?',
                            description: `If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?`,
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
import { reactive, ref } from "@vue/reactivity";
import { inject } from "@vue/runtime-core";
import SingleSelect from "@/components/form/SingleSelect.vue";
import CompanySelect from "./components/CompanySelect.vue";
import { trans } from "laravel-vue-i18n";
import { useVuelidate } from "@vuelidate/core";
import Back from "@/components/form/Back.vue";
import { useIsEqual } from "@/composables/utils.js";
import {
    required,
    email,
    minLength,
    maxLength,
    helpers,
} from "@vuelidate/validators";
import PhoneNumberInput from "../../components/form/PhoneNumberInput.vue";
import { useRoute, useRouter } from "vue-router";
import dayjs from "dayjs";
import { notificationShowStore } from "@/store/notification";
import { usePermissionStore } from "@/composables/permission";

let customerCompanies = ref([]);
let prefix_id = ref("");
let creationDate = ref("");


const vueRoute = useRoute();
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
        language_id: "",
        phone_iso_code: "",
        phone_number: "",
        user_id: "",
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

const v$ = useVuelidate(rules, form);

const formatDate = (value) => {
    return dayjs(value).format("DD.MM.YYYY");
};

// get company list
const getCompanyData = async () => {
    try {
        const { data } = await axios.get(route("customer-companies.get-list"));
        customerCompanies.value = data;
    } catch (error) {
        console.log(error);
    }
};

// show the customer company admin info
const getCustomerCompanyAdminData = async () => {
    try {
        const { data } = await axios.get(
            route(`customer-company-admins.show`, vueRoute.params.id)
        );
        // console.log('data.customer_company.id',data.customer_company.id)
        form.customer_company_id = data.customer_company.id;
        creationDate.value = formatDate(data.created_at);
        form.first_name = data.user.first_name;
        form.last_name = data.user.last_name;
        form.email = data.user.email;
        form.language_id = data.user.language_id;
        form.phone_iso_code = data.phone_iso_code ?? "";
        form.phone_number = data.phone_number ?? "";
        prefix_id.value = data.prefix_id;
        form.user_id = data.user.id;

        //assign customer company value to form
        Object.keys(form.data()).forEach((key) => {
            form.originalData[key] = form[key];
        });
    } catch (error) {
        console.log(error);
    }
};

// form submition
const submit = async () => {
    v$.value.$touch();
    if (v$.value.$invalid) {
        $vfm.show("field-missing");
        return;
    }

    try {
        const { data } = await form.put(
            route(`customer-company-admins.update`, {
                customer_company_admin: vueRoute.params.id,
            }),
            { form }
        );
        const notificationStore = notificationShowStore();
        notificationStore.success(trans(data.message), trans("Notification"));
        router.back();
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

// email exist backend check notifications
let notification = (message, title) => {
    $vfm.show("email-exist-alert", {
        title: title,
        description: message,
    });
};

getCustomerCompanyAdminData();
getCompanyData();

if (!permission.hasPermission("customer-company-admin:edit")) {
    // console.log('has access')
    router.push({ name: "403" });
}
</script>

<style scoped></style>

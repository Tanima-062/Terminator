<template>
    <div
      class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
        {{ $t("Add New Broker User") }}
        </h1>

        <form class="form grid grid-cols-3 gap-x-[251px]" @submit.prevent="submit">
            <div class="flex flex-col">
                <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Broker User ID") }}*
                </h2>
                <p class="text-value text-4 font-semibold font-inter leading-[19px]">
                    {{ prefix_id }}
                </p>
            </div>
            <SingleSelect
                label="Broker"
                placeholder="Select Broker"
                asterisk="true"
                :options="formatedBrokers"
                v-model="form.broker_id"
                optionsClass="w-full right-0"
                :error="v$.broker_id.$errors.length > 0"
            />
            <SingleSelect
                label="Role"
                placeholder="Select role"
                asterisk="true"
                :options="roles"
                v-model="form.role"
                optionsClass="w-full right-0"
                :error="v$.role.$errors.length > 0"
            />

            <div class="spacer mt-[55px] col-span-3"></div>

            <SingleLanguageSelect
                v-model="form.correspondence_language"
                label="Correspondence Language"
                placeholder="Correspondence language select"
                labelClass="whitespace-nowrap"
                :asterisk="true"
                :searchable="true"
                :error="v$.correspondence_language.$errors.length > 0"
            />
            <SingleSelect
                label="System language"
                placeholder="Select language"
                asterisk="true"
                :options="formatedLanguage"
                v-model="form.language_id"
                optionsClass="w-full right-0"
                :error="v$.language_id.$errors.length > 0"
            />

            <div class="spacer mt-[55px] col-span-3"></div>

            <SingleSelect
                label="Salutation"
                placeholder="Select salutation"
                asterisk="true"
                :options="salutations"
                v-model="form.salutation"
                optionsClass="w-full right-0"
                :error="v$.salutation.$errors.length > 0"
            />

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
            <div class="spacer col-span-3 mt-[35px]"></div>

            <TextInput
                label="Email"
                placeholder="Enter e-mail"
                :asterisk="true"
                v-model="form.email"
                :error="v$.email.$errors[0]?.$message"
                @blur="
                    () => {
                    v$.email.$touch();
                    }
                "
            />

            <PhoneNumberInput
                label="Phone Number"
                v-model:country_code="form.phone_iso_code"
                v-model:phone_number="form.phone"
            />
            <div class="spacer col-span-3 mt-[85px]"></div>
            <div
                class="logout-time-input flex items-center col-span-2 gap-[10px]"
            >
                <div class="wrapper w-[6%]">
                    <ToggleSwitch v-model="form.send_mail" />
                </div>
                <span class="text-title font-poppins font-semibold text-xl">{{
                    $t("Send email invitation to user")
                }}</span>
            </div>
            <div class="spacer col-span-3 mb-[80px]"></div>
            <div class="flex col-span-2 gap-[18px]">
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
                            title: 'Cancel broker user Registration?',
                            description: 'When you abort, all data is discarded. Are you sure you really want to cancel this broker user registration?',
                        })
                    "
                >
                    {{ $t("Cancel") }}
                </ButtonWhite>
            </div>
        </form>
    </div>
  </template>

  <script setup>
    import ButtonGradient from "@/components/button/Gradient.vue";
    import ButtonWhite from "@/components/button/White.vue";
    import TextInput from "@/components/form/TextInput.vue";
    import ToggleSwitch from "@/components/form/ToggleSwitch.vue";
    import PhoneNumberInput from "@/components/form/PhoneNumberInput.vue";
    import SingleSelect from "@/components/form/SingleSelect.vue";
    import SingleLanguageSelect from "../../components/form/SingleLanguageSelect.vue";
    import { storeToRefs } from "pinia";
    import { brokersStore } from "@/store/brokers.js";
    import { languageStore } from "@/store/language.js";
    import { reactive, computed, ref } from "@vue/reactivity";
    import { inject, onBeforeMount } from "@vue/runtime-core";
    import { useRouter } from "vue-router";
    import Form from "vform";
    import { useVuelidate } from "@vuelidate/core";
    import { required, email, maxLength, helpers } from "@vuelidate/validators";
    import { trans } from "laravel-vue-i18n";
    import { notificationShowStore } from "@/store/notification.js";
    import {usePermissionStore} from '@/composables/permission'
    const permission = usePermissionStore();

    const notificationForSuccess = notificationShowStore();

    const { fetchBroker } = brokersStore();
    fetchBroker();
    const { formatedBrokers } = storeToRefs(brokersStore());
    const { formatedLanguage } = storeToRefs(languageStore());
    const salutations = [
            { value: "Ms", label: "Ms" },
            { value: "Mr", label: "Mr" },
            { value: "/", label: "/" },
    ];
    const roles = [
        {value: "Admin", label: "Admin"},
        {value: "Intermediary", label: "Intermediary"}
    ];
    const prefix_id = ref("");
    const router = useRouter();


    const $vfm = inject("$vfm");

    const form = reactive(
        new Form({
            prefix_id: prefix_id.value,
            broker_id: '',
            role: '',
            correspondence_language: null,
            language_id: 1,
            salutation: '',
            first_name: '',
            last_name: '',
            email: "",
            phone_iso_code: "",
            phone: "",
            send_mail: false,
        })
    );

    const rules = {
        first_name: {
            required: helpers.withMessage("", required),
            maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        },
        last_name: {
            required: helpers.withMessage("", required),
            maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        },
        correspondence_language: {
            required: helpers.withMessage("", required),
        },
        language_id: {
            required: helpers.withMessage("", required),
        },
        salutation: {
            required: helpers.withMessage("", required),
        },
        broker_id: {
            required: helpers.withMessage("", required),
        },
        role: {
            required: helpers.withMessage("", required),
        },
        email: {
            required: helpers.withMessage("", required),
            email: helpers.withMessage(trans("Invalid Email format"), email),
        },
    };

    const v$ = useVuelidate(rules, form);

    const submit = async () => {
        v$.value.$touch();
        if (v$.value.$invalid) {
            $vfm.show("field-missing");
            return;
        }
        try {
            const res = await form.post(route("broker-users.store"));

            const message = form.send_mail ? 'The broker user was successfully created and the Email invitation sent' : 'The broker user was successfully created'
            notificationForSuccess.success({ text: message });
            router.push({ name: "broker-user-index" });
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

    onBeforeMount(() => {
        getNextPrefixId();
    });

    const getNextPrefixId = async () => {
        try {
        const res = await axios.get(route("broker-users.get-prefixId"));
        prefix_id.value = res.data;
        } catch (error) {
            console.log(error);
        }
    };

    if( !permission.hasPermission('broker-user:edit')){
        router.push({ name: '403' })
    }
  </script>

  <style scoped></style>

<template>
    <div
      class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
        <Back class="mb-[33px]" :show_modal="!useIsEqual(form.data(), form.originalData)" />
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
        {{ $t("Edit broker user") }}
        </h1>

        <form class="form grid grid-cols-3 gap-x-[251px]" @submit.prevent="submit">
            <div class="flex flex-col">
                <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                {{ $t("Creation Date") }}
                </h2>
                <p
                class="text-value text-4 font-semibold font-inter leading-[19px]"
                v-date-format="brokerUser.created_at"
                ></p>
            </div>
            <div class="flex flex-col">
                <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Broker User ID") }}*
                </h2>
                <p class="text-value text-4 font-semibold font-inter leading-[19px]">
                    {{ brokerUser.prefix_id }}
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

            <div class="spacer mt-[55px] col-span-3"></div>

            <SingleSelect
                label="Role"
                placeholder="Select role"
                asterisk="true"
                :options="roles"
                v-model="form.role"
                optionsClass="w-full right-0"
                :error="v$.role.$errors.length > 0"
            />

            <SingleLanguageSelect
                v-model="form.correspondence_language"
                label="Correspondence Language"
                placeholder="Correspondence language select"
                labelClass="whitespace-nowrap"
                :asterisk="true"
                :searchable="false"
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

            <div class="flex flex-col">
                <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                    {{ $t("Email") }}*
                </h2>
                <p class="text-value text-4 font-semibold font-inter leading-[19px]">
                    {{ brokerUser?.user?.email }}
                </p>
            </div>

            <PhoneNumberInput
                label="Phone Number"
                v-model:country_code="form.phone_iso_code"
                v-model:phone_number="form.phone"
                :error="
                    v$.phone.$errors.length
                    ? true
                    : false
                "
            />
            <div class="spacer col-span-3 mt-[85px]"></div>
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
                            title: trans('Discard changes?'),
                            description: trans('If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?'),
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
    import {useRoute, useRouter} from 'vue-router'
    import { notificationShowStore } from "@/store/notification.js";
    import Form from "vform";
    import { useVuelidate } from "@vuelidate/core";
    import { required, email, maxLength, helpers } from "@vuelidate/validators";
    import { trans } from "laravel-vue-i18n";
    import Back from '@/components/form/Back.vue'
    import { useIsEqual } from '@/composables/utils.js'

    const notification = notificationShowStore();
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

    const vueRoute = useRoute();
    const router = useRouter();


    const $vfm = inject("$vfm");
    const brokerUser = reactive({});

    const form = reactive(
        new Form({
            prefix_id: brokerUser.prefix_id,
            broker_id: brokerUser.broker_id,
            user_id: brokerUser.user_id,
            role: '',
            correspondence_language: null,
            language_id: 1,
            salutation: '',
            first_name: '',
            last_name: '',
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
        phone: {
            valid: helpers.withMessage('', helpers.regex(/^\d*$/)),
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
            let data = form.data();
            const res = await axios.put(
                route("broker-users.update", {
                    broker_user: brokerUser.id,
                }),
                data
            );
            router.push({ name: "broker-user-index" });
            notification.success({ text: 'The Broker User “:name” was successfully updated.', replace: { name: res.data.user.full_name } })
            } catch (error) {
            console.log(error);
        }
  };

    onBeforeMount(() => {
        getBrokerDetails()
    });

    const getBrokerDetails = async () => {
        try {
            const res = await axios.get(
                route("broker-users.show", {
                    broker_user: vueRoute.params.id,
                })
            );
            Object.assign(brokerUser, res.data);

            //assign broker value to form
            Object.keys(form.data()).forEach((key) => {
                if (brokerUser.hasOwnProperty(key)) {
                    form[key] = brokerUser[key];
                    form.originalData[key] = brokerUser[key]
                }
                form.first_name = brokerUser.user.first_name
                form.last_name = brokerUser.user.last_name
                form.originalData.first_name = brokerUser.user.first_name
                form.originalData.last_name = brokerUser.user.last_name
            });
        } catch (error) {
            // console.log(error);
            if(error.response.status == 403){
                router.push({ name: '403' })
            }
        }
    };



  </script>

  <style scoped></style>

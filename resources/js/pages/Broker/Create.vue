<template>
    <div
      class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
      <h1 class="text-formHeading text-title font-poppins mb-[60px]">
        {{ $t("Add New Broker") }}
      </h1>

      <form class="form grid grid-cols-3 gap-x-[251px]" @submit.prevent="submit">
        <!-- First Row -->
        <div class="flex flex-col">
          <h2 class="label text-formLabel font-inter text-input mb-[10px]">
            {{ $t("Broker ID") }}*
          </h2>
          <p class="text-value text-4 font-semibold font-inter leading-[19px]">
            {{ prefix_id }}
          </p>
        </div>

        <TextInput
          label="Name"
          :placeholder="'Enter Broker Name'"
          :asterisk="true"
          maxLength="51"
          v-model="form.name"
          :error="v$.name.$errors[0]?.$message"
          @input="
            () => {
              v$.name.$touch();
            }
          "
        />

        <!-- Second Row -->
        <h1
          class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[34px]"
        >
          {{ $t("Address") }}
        </h1>

        <TextInput
          label="Street Name"
          :placeholder="'Enter Street Name'"
          maxLength="31"
          v-model="form.street_name"
          :error="v$.street_name.$errors[0]?.$message"
          @input="
            () => {
              v$.street_name.$touch();
            }
          "
        />

        <TextInput
          label="Street Number"
          :placeholder="'Enter Street Number'"
          maxLength="31"
          v-model="form.street_number"
          :error="v$.street_number.$errors[0]?.$message"
          @input="
            () => {
              v$.street_number.$touch();
            }
          "
        />

        <TextInput
          label="Zip Code"
          :placeholder="'Enter Zip Code'"
          v-model="form.zip_code"
          :error="
          v$.zip_code.$errors.length
            ? v$.zip_code.validLength.$invalid
              ? form.country_iso_code == 'ch'
                ? $t('Maximum :length characters possible', { length: 4 })
                : $t('Maximum :length characters possible', { length: 10 })
              : true
            : false
        "
        @input="v$.zip_code.$touch()"
        />

        <!-- Third Row -->
        <div class="spacer mt-[35px] col-span-3"></div>

        <TextInput
          label="City"
          :placeholder="'Enter City'"
          maxLength="31"
          v-model="form.city"
          :error="v$.city.$errors[0]?.$message"
          @input="
            () => {
              v$.city.$touch();
            }
          "
        />

        <CountrySelect v-model="country_iso_code" label="Country" placeholder="Select Country" />

        <!-- Fourth Row -->
        <h1
          class="row-header text-heading text-formSubHeading font-inter col-span-3 mt-[47px] mb-[34px]"
        >
          {{ $t("Contact Person") }}
        </h1>

        <TextInput
          label="First Name"
          :placeholder="'Enter First Name'"
          :asterisk="true"
          maxLength="31"
          v-model="form.contact_person_first_name"
          :error="v$.contact_person_first_name.$errors[0]?.$message"
          @input="
            () => {
              v$.contact_person_first_name.$touch();
            }
          "
        />

        <TextInput
          label="Last Name"
          :placeholder="'Enter Last Name'"
          :asterisk="true"
          maxLength="31"
          v-model="form.contact_person_last_name"
          :error="v$.contact_person_last_name.$errors[0]?.$message"
          @input="
            () => {
              v$.contact_person_last_name.$touch();
            }
          "
        />

        <div class="spacer col-span-3 mt-[35px]"></div>

        <TextInput
          label="Email Address (*)"
          :placeholder="'Enter Email Address'"
          v-model="form.contact_person_email"
          :error="v$.contact_person_email.$errors[0]?.$message"
          @blur="
            () => {
              v$.contact_person_email.$touch();
            }
          "
        />

        <PhoneNumberInput
          label="Phone Number (*)"
          v-model:country_code="form.contact_person_phone_iso_code"
          v-model:phone_number="form.contact_person_phone"
          :error="
            v$.contact_person_phone_iso_code.$errors.length ||
            v$.contact_person_phone.$errors.length
              ? true
              : false
          "
        />

        <div class="spacer mt-[100px] col-span-3"></div>
        <div class="flex col-span-2 gap-[18px]">
          <ButtonGradient class="w-[20%]" type="submit" :disabled="form.busy">
            {{ $t("Save") }}
          </ButtonGradient>

          <ButtonWhite
            class="w-[20%]"
            type="button"
            @click="
              $vfm.show('redirect-modal', {
                title: trans('Cancel Broker Registration?'),
                description: trans('When you abort, all data is discarded. Are you sure you really want to cancel this broker registration?'),
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
  import Form from "vform";
  import RadioInput from "@/components/form/RadioInputs.vue";
  import { reactive, computed, ref } from "@vue/reactivity";
  import { inject, onBeforeMount } from "@vue/runtime-core";
  import MultiSelect from "@/components/form/MultiSelect.vue";
  import PlusIcon from "@/components/icons/Plus.vue";
  import { trans } from "laravel-vue-i18n";
  import CountrySelect from "@/components/form/CountrySelect.vue";
  import { useVuelidate } from "@vuelidate/core";
  import { required, email, maxLength, helpers } from "@vuelidate/validators";
  import PhoneNumberInput from "../../components/form/PhoneNumberInput.vue";
  import axios from "axios";
  import { notificationShowStore } from "@/store/notification.js";
  import { useRouter } from "vue-router";

  const notification = notificationShowStore();


  const prefix_id = ref("");

  const router = useRouter();


  const $vfm = inject("$vfm");

  const form = reactive(
    new Form({
      prefix_id: prefix_id.value,
      name: "",
      street_name: "",
      street_number: "",
      zip_code: "",
      city: "",
      country_iso_code: "",
      contact_person_first_name: "",
      contact_person_last_name: "",
      contact_person_email: "",
      contact_person_phone_iso_code: "",
      contact_person_phone: "",
    })
  );

  const country_iso_code = computed({
    get: () => form.country_iso_code,
    set: (value) => {
      form.country_iso_code = value;
      if (!form.contact_person_phone_iso_code || !form.contact_person_phone)
        form.contact_person_phone_iso_code = value;
    },
  });

  const rules = {
    name: {
      required: helpers.withMessage("", required),
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 50 }), maxLength(50)),
    },
    street_name: {
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    },
    street_number: {
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    },
    city: {
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    },
    contact_person_first_name: {
      required: helpers.withMessage("", required),
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
      validFormat: helpers.withMessage("", helpers.regex(/^[\p{L}\p{M}\p{Zs}\-]+$/u)),
    },
    contact_person_last_name: {
      required: helpers.withMessage("", required),
      maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
      validFormat: helpers.withMessage("", helpers.regex(/^[\p{L}\p{M}\p{Zs}\-]+$/u)),
    },
    contact_person_email: {
      required: helpers.withMessage("", (value) =>
        form.contact_person_phone ? true : value.length ? true : false
      ),
      email: helpers.withMessage("Invalid Email format", email),
    },
    contact_person_phone_iso_code: {
      reuqired: helpers.withMessage("", (value) =>
        form.contact_person_email ? true : value.length ? true : false
      ),
    },
    contact_person_phone: {
      reuqired: helpers.withMessage("", (value) =>
        form.contact_person_email ? true : value.length ? true : false
      ),
      valid: helpers.withMessage('', helpers.regex(/^\d*$/)),
    },
    zip_code: {
      validFormat: helpers.withMessage('', (value) => {
            if(form.zip_code == null || form.zip_code == '') return true
            else{
                if (form.country_iso_code == 'ch') return /^[\d]*$/.test(value)
                    return value.length < 1 ? true : /^[\d\p{L}\p{M}\p{Zs}\-]+$/u.test(value)
            }
        }),
        validLength: helpers.withMessage('', (value) => {
            if(form.zip_code == null || form.zip_code == '') return true
            else{
                if (form.country_iso_code == 'ch') return value.length < 5
                    return value.length < 11
            }
        }),
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
      const res = await form.post(route("brokers.store"));
      router.push({ name: "broker-index" });
      notification.success('The broker was successfully created.')
    } catch (error) {
      console.log(error, ["error"]);
    }
  };

  onBeforeMount(() => {
    getNextPrefixId();
  });

  const getNextPrefixId = async () => {
    try {
      const res = await axios.get(route("brokers.get-prefixId"));
      prefix_id.value = res.data;
    } catch (error) {
      console.log(error);
    }
  };
  </script>

  <style scoped></style>

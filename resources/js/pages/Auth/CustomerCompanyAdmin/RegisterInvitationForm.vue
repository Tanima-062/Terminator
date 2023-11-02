<template>
    <AuthLayout>
        <div>
            <p
                class="text-[#81767F] font-inter font-medium text-4xl mt-[25px] mb-[10px] xs:text-2xl xs:mb-[10px]"
            >
                {{ $t("Welcome") }} <br />
                {{ name }}
            </p>
            <p class="text-gray font-inter text-nd mt-[15px] mb-[35px]">
                {{
                    $t(
                        "Please set and register your personal password to use Termin-ator as an administrator."
                    )
                }}
            </p>
            <h2
                class="text-heading font-inter font-semibold text-[30px] mt-[5px] mb-[10px] xs:text-2xl xs:mb-[10px]"
            >
                {{ $t("Registration") }}
            </h2>
            <h2
                class="text-md text-btnGradient2 font-medium text-mt-[24px] mb-0 font-inter xs:text-[18px]"
            >
                {{ $t("E-Mail Address") }}
            </h2>
            <h2
                class="font-inter text-16 text-[#555555] mb-8 mt-4 xs:text-[18px]"
            >
                {{ form.email }}
            </h2>

            <form method="POST" @submit.prevent="">
                <div class="form-group">
                    <div class="mt-1">
                        <PasswordInput
                            :label="'Password'"
                            type="password"
                            :placeholder="$t('Enter Password')"
                            id="password"
                            labelClass="block text-md text-btnGradient2 font-medium text-label text-mt-[24px] mb-0 font-inter xs:text-[18px]"
                            v-model="form.password"
                            :showTooltip="true"
                            :error="
                                v$.password.$errors[0]
                                    ? $t(
                                          'The password does not fulfil the password conditions'
                                      )
                                    : '' || form.errors.errors.password
                                    ? form.errors.errors.password[0]
                                    : ''
                            "
                            @input="() => v$.password.$touch()"
                        />
                    </div>
                </div>
                <div class="form-group">
                    <div class="mt-1">
                        <PasswordInput
                            :label="`${$t('Confirm Password')}`"
                            type="password"
                            :placeholder="$t('Enter Confirm Password')"
                            id="confirmPassword"
                            :error="
                                form.errors.errors.password_confirmation
                                    ? form.errors.errors
                                          .password_confirmation[0]
                                    : '' ||
                                      v$.password_confirmation.$errors[0]
                                          ?.$message
                            "
                            labelClass="block text-md text-btnGradient2 font-medium text-label mt-8 mb-0 font-inter xs:text-[18px]"
                            v-model="form.password_confirmation"
                            @input="() => v$.password_confirmation.$touch()"
                        />
                    </div>
                </div>
                <div class="mt-[58px] xs:my-[25px]">
                    <ButtonGradient
                        :disabled="v$.$error || v$.$invalid"
                        class="disabled:bg-[#BBBBBB] disabled:from-[#BBBBBB] disabled:to-[#BBBBBB] disabled:shadow-none"
                        @click="submit"
                        >{{ $t("Registrieren") }}</ButtonGradient
                    >
                </div>
                <div
                    class="mt-[32px]"
                    v-if="
                        v$.password.$invalid && v$.password.$errors[0]?.$message
                    "
                >
                    <p
                        class="mb-2 font-normal font-inter text-error text-[13px]"
                        v-if="v$.$anyDirty"
                    >
                        {{
                            $t(
                                "The password does not fulfil the following conditions:"
                            )
                        }}
                    </p>

                    <div
                        class="messages mb-2 flex gap-2"
                        v-for="error of v$.password.$errors"
                        :key="error.$uid"
                    >
                        <div class="mt-[6px]">
                            <failed-icon />
                        </div>
                        <div
                            class="font-normal font-lato text-error text-[13px]"
                        >
                            <div class="">
                                {{ $t(error.$message) }}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>
<script setup>
import Form from "vform";
import AuthLayout from "@/layouts/Auth.vue";
import ButtonGradient from "@/components/button/Gradient.vue";
import PasswordInput from "@/components/form/PasswordInput.vue";
import FailedIcon from "@/components/icons/FailedIcon.vue";
import { helpers, required, minLength } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import { trans } from "laravel-vue-i18n";
import { useRouter, useRoute } from "vue-router";
import { ref, computed, reactive, onMounted } from "vue";

const router = useRouter();
const route_param = useRoute();
let name = ref("");
let language = ref("en");
const form = reactive(
    new Form({
        email: "",
        token: "",
        password: "",
        password_confirmation: "",
    })
);

form.email = window.location.search.substr(1).split("&")[2].split("=")[1];
form.token = route_param.query.token;
name.value = route_param.query.name;
language.value = route_param.query.lang;

const capitalLetter = helpers.withMessage(
    trans("At least 1 upper case letter"),
    helpers.regex(/[A-Z]/)
);
const smallLetter = helpers.withMessage(
    trans("At least 1 lower case letter"),
    helpers.regex(/[a-z]/)
);
const number = helpers.withMessage(
    trans("At least one number"),
    helpers.regex(/[0-9]/)
);
const specialCharacter = helpers.withMessage(
    trans("At least one special character"),
    helpers.regex(/[@#$%^&+=!<>\*\?]/)
);

const rules = computed(() => {
    return {
        password: {
            required: helpers.withMessage("", required),
            minLength: helpers.withMessage(
                trans("At least 8 characters"),
                minLength(8)
            ),
            capitalLetter,
            smallLetter,
            number,
            specialCharacter,
        },
        password_confirmation: {
            required: helpers.withMessage("", required),
            sameAsPassword: helpers.withMessage(
                trans("Password and confirmation do not match."),
                () => form.password == form.password_confirmation
            ),
        },
    };
});

const v$ = useVuelidate(rules, form);

const submit = async () => {
    try {
        v$.value.password.$touch();
        v$.value.password_confirmation.$touch();
        const validate = await v$.value.$validate();
        if (validate) {
            await form.post(route("customer-company-admins.accept-invitaion"));
            router.push({
                name: "register-invitation-success",
                query: { lang: "de" },
            });
        }
    } catch (error) {
        if (error.response.data.errors.token == "reset_token_invalid") {
            router.push({ name: "not_found_guest" });
        }
    }
};
const tokenCheck = async () => {
    try {
        // console.log(form);
        await form.post(route("token.validate-email"));
        loadPage.value = true;
    } catch (error) {
        // console.log('hello')
        // console.log("reset page", error.response.data.errors.token);
        if (error.response.data.errors.token == "reset_token_invalid") {
            router.push({ name: "not_found_guest" });
        }
    }
};
onMounted(() => {
    tokenCheck();
});
</script>

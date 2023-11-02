<template>
    <AuthLayout>
        <div>
            <h2
                class="text-heading font-inter font-semibold text-3xl mt-[89px] mb-[34px] xs:text-2xl xs:mb-[10px]"
            >
                {{ $t("Password forgotten?") }}
            </h2>
            <p class="text-gray font-inter text-nd mt-[15px] mb-[45px]">
                {{
                    $t(
                        "No problem! Please enter your registered email address and we will send you the instructions to reset your password"
                    )
                }}
            </p>

            <form method="POST" @submit.prevent="submit">
                <div class="form-group">
                    <div class="mt-1">
                        <TextInput
                            :label="`${$t('E-Mail')}`"
                            :placeholder="$t('Enter E-Mail Address')"
                            labelClass="block text-btnGradient2 text-md font-medium text-label font-inter xs:text-[18px]"
                            :error="
                                form.errors.errors.email
                                    ? form.errors.errors.email[0]
                                    : '' || v$.email.$errors[0]?.$message
                            "
                            maxLength="41"
                            v-model="form.email"
                            @input="() => v$.email.$touch()"
                        />
                    </div>
                </div>
                <div class="mt-[58px] xs:my-[25px]">
                    <ButtonGradient :disableld="form.busy" type="submit">{{
                        $t("Send Email")
                    }}</ButtonGradient>
                    <WhiteButtonForget
                        class="mt-5"
                        @click="goToLogin()"
                        type="button"
                        >{{ $t("Back to Login") }}</WhiteButtonForget
                    >
                </div>
            </form>
        </div>
    </AuthLayout>
</template>
<script setup>
import Form from "vform";
import AuthLayout from "@/layouts/Auth.vue";
import ButtonGradient from "@/components/button/Gradient.vue";
import WhiteButtonForget from "@/components/button/WhiteButtonForget.vue";
import TextInput from "@/components/form/TextInput.vue";
import { email, helpers, required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import { trans } from "laravel-vue-i18n";
import { useRouter } from "vue-router";
import { reactive, computed } from "vue";
import { notificationShowStore } from "@/store/notification";

const notificationStore = notificationShowStore();
const router = useRouter();
const form = reactive(
    new Form({
        email: "",
    })
);

const rules = computed(() => {
    return {
        email: {
            required: helpers.withMessage(trans("Email required"), required),
            email: helpers.withMessage(
                trans("Invalid email, please add a valid email address"),
                email
            ),
        },
    };
});

const v$ = useVuelidate(rules, form);

const submit = async () => {
    try {
        v$.value.email.$touch();
        const validate = await v$.value.$validate();
        if (validate) {
            await form.post(route("password.email"));
            notificationStore.success(
                trans("Thank you very much. The email was sent successfully."),
                trans("Notification")
            );
            router.push({ name: "login" });
        }
    } catch (error) {
        if (error.response.data.errors.user)
            form.errors.errors = error.response.data.errors;
        //console.log("forgot page", error.response.data.errors.user[0]);
    }
};

const goToLogin = () => {
    router.push({ name: "login" });
};
</script>

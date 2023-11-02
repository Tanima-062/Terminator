<template>
    <AuthLayout>
        <div>
            <h2
                class="font-poppins text-[#81767F] mt-[75px] xs:mt-[25px] text-[35px] leading-[38px] font-medium"
            >
                {{ $t("Welcome to Termin-ator") }}
            </h2>
            <h2
                class="text-heading font-inter text-[30px] leading-[38px] font-semibold mt-10 mb-[20px]"
            >
                {{ $t("login") }}
            </h2>

            <form method="POST" @submit.prevent="login">
                <div class="mb-3 form-group">
                    <label
                        for="email"
                        class="block text-md font-medium text-label font-inter xs:text-[18px] text-[#8B387F]"
                        >{{ $t("E-Mail Address") }}*</label
                    >
                    <div class="mt-1">
                        <input
                            id="email"
                            type="text"
                            name="email"
                            v-model="form.email"
                            :placeholder="$t('Enter E-Mail Address')"
                            @input="
                                () => {
                                    v$.email.$touch();
                                    unauthenticated = false;
                                }
                            "
                            class="h-10 block w-full font-inter text-base text-bodyText border border-[#E6DEE5] rounded-md py-[4px] px-[16px] bg-white focus:outline-0"
                            :class="{
                                'border-[#F93232]':
                                    unauthenticated || v$.email.$errors.length,
                            }"
                        />
                    </div>
                </div>

                <div class="mb-3 form-group">
                    <label
                        for="password"
                        class="block text-md font-medium text-label font-inter xs:text-[18px] text-[#8B387F]"
                        >{{ $t("Password") }}*</label
                    >
                    <div class="mt-1 relative">
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            v-model="form.password"
                            :placeholder="$t('Enter Password')"
                            class="h-10 block w-full text-base font-inter text-bodyText border border-[#E6DEE5] rounded-md py-[4px] px-[16px] bg-white focus:outline-0"
                            @input="
                                () => {
                                    v$.password.$touch();
                                    unauthenticated = false;
                                }
                            "
                            :class="{
                                'border-[#F93232]':
                                    unauthenticated ||
                                    v$.password.$errors.length,
                            }"
                        />
                        <div
                            class="inline-block absolute right-[13px] top-[30%] hover:cursor-pointer"
                            @click="showPassword = !showPassword"
                        >
                            <div v-if="showPassword">
                                <EyeOpen />
                            </div>
                            <div v-else>
                                <EyeClose />
                            </div>
                        </div>
                    </div>

                    <div class="block w-full text-right mt-2">
                        <router-link
                            :to="{ name: 'reset-password' }"
                            href="#"
                            class="no-underline text-[#008DB1] font-inter text-[13px]"
                            >{{ $t("Password forgotten?") }}</router-link
                        >
                    </div>
                </div>

                <div class="mt-[64px]">
                    <ButtonGradient type="submit" class="shadow-none">{{
                        $t("Log in")
                    }}</ButtonGradient>
                </div>
                <div
                    class="flex w-full mt-3.5 text-red-600"
                    v-if="unauthenticated"
                >
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 14 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        style="margin-top: 3px; margin-right: 8px"
                    >
                        <path
                            d="M7.00033 13.2709C3.54116 13.2709 0.729492 10.4592 0.729492 7.00008C0.729492 3.54091 3.54116 0.729248 7.00033 0.729248C10.4595 0.729248 13.2712 3.54091 13.2712 7.00008C13.2712 10.4592 10.4595 13.2709 7.00033 13.2709ZM7.00033 1.60425C4.02533 1.60425 1.60449 4.02508 1.60449 7.00008C1.60449 9.97508 4.02533 12.3959 7.00033 12.3959C9.97533 12.3959 12.3962 9.97508 12.3962 7.00008C12.3962 4.02508 9.97533 1.60425 7.00033 1.60425Z"
                            fill="#F93232"
                        />
                        <path
                            d="M5.34913 9.08829C5.23829 9.08829 5.12746 9.04746 5.03996 8.95996C4.87079 8.79079 4.87079 8.51079 5.03996 8.34163L8.34163 5.03996C8.51079 4.87079 8.79079 4.87079 8.95996 5.03996C9.12913 5.20913 9.12913 5.48913 8.95996 5.65829L5.65829 8.95996C5.57663 9.04746 5.45996 9.08829 5.34913 9.08829Z"
                            fill="#F93232"
                        />
                        <path
                            d="M8.65079 9.08829C8.53996 9.08829 8.42913 9.04746 8.34163 8.95996L5.03996 5.65829C4.87079 5.48913 4.87079 5.20913 5.03996 5.03996C5.20913 4.87079 5.48913 4.87079 5.65829 5.03996L8.95996 8.34163C9.12913 8.51079 9.12913 8.79079 8.95996 8.95996C8.87246 9.04746 8.76163 9.08829 8.65079 9.08829Z"
                            fill="#F93232"
                        />
                    </svg>

                    <div
                        class="block w-full text-[#F93232] text-[12px] leading-[19px] font-roboto"
                        v-html="
                            $t(
                                'Invalid login information. Please try again or click “:link” should you forget your password.',
                                {
                                    link: `<a href=reset-password>${$t(
                                        'Forgot Password'
                                    )}</a>`,
                                }
                            )
                        "
                    ></div>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>

<script setup>
import Form from "vform";
import { useVuelidate } from "@vuelidate/core";
import { email, required } from "@vuelidate/validators";
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";

import { useUserStore } from "@/store/user";

import ButtonGradient from "@/components/button/Gradient.vue";
import AuthLayout from "@/layouts/Auth.vue";
import EyeOpen from "@/components/icons/EyeOpen.vue";
import EyeClose from "@/components/icons/EyeClose.vue";

const userStore = useUserStore();
const router = useRouter();

const form = reactive(
    new Form({
        email: "",
        password: "",
    })
);

const showPassword = ref(false);
const unauthenticated = ref(false);

const rules = {
    email: { required, email },
    password: { required },
};

const v$ = useVuelidate(rules, form);

const login = async () => {
    try {
        v$.value.$touch();
        if (v$.value.$invalid) {
            return;
        }
        const res = await form.post(route("login"));
        userStore.setData(res.data);


        if (res.data.user.type == "system_admin") {
            router.push({ name: "customer-company" });
        } else {
            router.push({ name: "dashboard" });
        }
    } catch (error) {
        console.log("login page", error.response.status);
        if (error.response.status == 401) {
            unauthenticated.value = true;
        }
    }
};
</script>

<style scoped></style>

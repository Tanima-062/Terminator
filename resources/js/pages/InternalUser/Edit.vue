<template>
    <div
        class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] pr-[127px] rounded-[15px]"
    >
        <Back
            class="mb-[33px]"
            :show_modal="!useIsEqual(form.data(), form.originalData)"
        />
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
            {{ $t("Edit internal user") }}
        </h1>
        <form
            class="form grid grid-cols-3 gap-x-[21px]"
            @submit.prevent="submit"
        >
            <!-- First Row -->
            <div class="flex flex-col">
                <h2
                    class="label text-formLabel font-inter text-input mb-[10px]"
                >
                    {{ $t("User ID") }}
                </h2>
                <p
                    class="text-value text-4 font-semibold font-inter leading-[19px]"
                >
                    {{ internal_user.prefix_id }}
                </p>
            </div>
            <div class="flex flex-col">
                <h2
                    class="label text-formLabel font-inter text-input mb-[10px]"
                >
                    {{ $t("Creation Date") }}
                </h2>
                <p
                    class="text-value text-4 font-semibold font-inter leading-[19px]"
                    v-date-format="internal_user.created_at"
                ></p>
            </div>
            <!---Roles--->
            <SingleSelect
                label="Role"
                placeholder="Select role"
                asterisk="true"
                :options="formatedRoles"
                v-model="form.roles_id"
                optionsClass="w-full right-0"
                :error="v$.roles_id.$errors.length > 0"
            />
            <!---End Roles--->
            <div
                class="spacer col-span-3 mb-[35px]"
                v-if="
                    hierarchy_elements.length ||
                    none_lavel_hierarchy_elements?.length
                "
            ></div>
            <div
                class="wrappeer spacer col-span-3 grid grid-cols-3 gap-x-6"
                :class="[
                    hierarchy_elements?.length ? 'gap-y-[48px]' : 'gap-y-6',
                ]"
            ></div>
            <!-- Start Second Row -->
            <!---Campaign--->
            <MultiSelect
                :options="campaignList"
                v-model="form.campaign_id"
                label="Campaign"
                :disabled="campaignList.length == 1 ? true : false"
                :placeholder="`${$t('Campaign')} ${$t('Select')}`"
                labelClass="whitespace-nowrap text-[18px] font-semibold"
                class="mb-[35px]"
            />
            <!--  All Hierarchy label by subordinate and later responsible as hierarchy -->
            <template v-if="hierarchy_elements.length">
                <SingleSelectHierarchy
                    valueKey="id"
                    labelKey="name"
                    v-for="(hierarchy, index) in hierarchy_elements"
                    :key="index"
                    :indexKey="index"
                    :options="hierarchy.organization_elements"
                    :disabled="index == 0 ? false : true"
                    v-model="
                        form.hirarchy_with_elements.find(
                            ({ hierarchy_id }) => hierarchy_id == hierarchy.id
                        ).organization_elements
                    "
                    :placeholder="`${hierarchy.name} ${$t('Select')}`"
                    :label="hierarchy.name"
                    labelClass="whitespace-nowrap"
                    :asterisk="true"
                    @onUpdate="
                        (value) => {
                            form.errors.clear(
                                `hirarchy_with_elements.${index}`
                            );
                            return index == 0
                                ? hierachy_parent_select(value, hierarchy.id)
                                : null;
                        }
                    "
                    :error="form.errors.has(`hirarchy_with_elements.${index}`)"
                />
            </template>

            <!-- All none hierarchy company wise top -->
            <template v-if="none_lavel_hierarchy_elements.length">
                <MultiSelect
                    valueKey="id"
                    labelKey="name"
                    v-for="(
                        none_hierarchy, index
                    ) in none_lavel_hierarchy_elements"
                    :key="index"
                    :options="none_hierarchy.organization_elements"
                    :label="none_hierarchy.name"
                    v-model="
                        form.none_hirarchy.find(
                            ({ hierarchy_id }) =>
                                hierarchy_id == none_hierarchy.id
                        ).organization_elements
                    "
                    :placeholder="`${none_hierarchy.name} ${$t('Select')}`"
                    :asterisk="true"
                    :error="
                        form.errors.has(`none_hirarchy_with_elements.${index}`)
                    "
                    @onUpdate="
                        (value) => {
                            if (value.length > 0) {
                                return form.errors.clear(
                                    `none_hirarchy_with_elements.${index}`
                                );
                            }
                        }
                    "
                    class="mb-[35px]"
                >
                    <template #options-top="{ search }">
                        <div class="gap-[10px]">
                            <div
                                class="select-all flex gap-[10px] items-center cursor-pointer pb-[10px]"
                                @click="
                                    () =>
                                        none_hierarchy.organization_elements
                                            .length !==
                                        findFormHierarOrgEle(
                                            form.none_hirarchy,
                                            none_hierarchy.id
                                        ).length
                                            ? selectAll(
                                                  form.none_hirarchy,
                                                  none_lavel_hierarchy_elements,
                                                  none_hierarchy.id
                                              )
                                            : null
                                "
                            >
                                <Checkbox
                                    :checked="
                                        none_hierarchy.organization_elements
                                            .length ==
                                        findFormHierarOrgEle(
                                            form.none_hirarchy,
                                            none_hierarchy.id
                                        ).length
                                    "
                                />
                                <p class="text-[#636363] text-16">
                                    {{ $t("All") }}
                                </p>
                            </div>
                            <div
                                class="select-none flex gap-[10px] items-center cursor-pointer"
                                @click="
                                    () =>
                                        findFormHierarOrgEle(
                                            form.none_hirarchy,
                                            none_hierarchy.id
                                        ).length
                                            ? deselectAll(
                                                  form.none_hirarchy,
                                                  none_hierarchy.id
                                              )
                                            : null
                                "
                            >
                                <Checkbox
                                    :checked="
                                        findFormHierarOrgEle(
                                            form.none_hirarchy,
                                            none_hierarchy.id
                                        ).length
                                    "
                                />
                                <p
                                    class="text-[#636363] text-16"
                                    :class="{
                                        'text-[#ADB5BD]': !findFormHierarOrgEle(
                                            form.none_hirarchy,
                                            none_hierarchy.id
                                        ).length,
                                    }"
                                >
                                    {{ $t("None") }}
                                </p>
                            </div>
                        </div>
                    </template>
                </MultiSelect>
            </template>

            <!-- Third Row -->

            <!--Access right--->
            <div
                class="wrappeer spacer col-span-3 grid grid-cols-3 gap-x-6"
                :class="[
                    hierarchy_elements?.length && form.access_right == 3
                        ? 'gap-y-[48px]'
                        : 'gap-y-6',
                ]"
            >
                <SingleSelect
                    v-if="form.roles_id == 1 || form.roles_id == 2"
                    label="Access rights"
                    placeholder="Select access rights"
                    asterisk="true"
                    :options="accessRight"
                    v-model="form.access_right"
                    optionsClass="w-full right-0"
                    :error="form.errors.has('access_right')"
                    @onUpdate="
                        () => {
                            form.access_right;
                            if (form.access_right.length > 0) {
                                return form.errors.clear('access_right');
                            }
                        }
                    "
                />
                <!-- Custome access right -->
                <template
                    v-if="
                        hierarchy_elements.length &&
                        form.access_right == 3 &&
                        (form.roles_id == 1 || form.roles_id == 2)
                    "
                >
                    <MultiSelect
                        valueKey="id"
                        labelKey="name"
                        v-for="(hierarchy, index) in hierarchy_elements"
                        :key="index"
                        :label="hierarchy.name"
                        :indexKey="index"
                        :options="hierarchy.organization_elements"
                        :topLabel="index == 0 ? true : false"
                        :hierarchyCustom="true"
                        v-model="
                            form.custom_hirarchy_with_elements.find(
                                ({ hierarchy_id }) =>
                                    hierarchy_id == hierarchy.id
                            ).organization_elements
                        "
                        :placeholder="`${hierarchy.name} ${$t('Select')}`"
                        :asterisk="true"
                        @onUpdate="
                            (value) => {
                                if (value.length > 0) {
                                    return form.errors.clear(
                                        `custom_hirarchy_with_elements.${index}`
                                    );
                                }
                            }
                        "
                        :error="
                            form.errors.has(
                                `custom_hirarchy_with_elements.${index}`
                            )
                        "
                    >
                        <template #options-top="{ search }">
                            <div class="gap-[10px]">
                                <div
                                    class="select-all flex gap-[10px] items-center cursor-pointer pb-[10px]"
                                    @click="
                                        () =>
                                            hierarchy.organization_elements
                                                .length !==
                                            findFormHierarOrgEle(
                                                form.custom_hirarchy_with_elements,
                                                hierarchy.id
                                            ).length
                                                ? selectAll(
                                                      form.custom_hirarchy_with_elements,
                                                      hierarchy_elements,
                                                      hierarchy.id
                                                  )
                                                : null
                                    "
                                >
                                    <Checkbox
                                        :checked="
                                            hierarchy.organization_elements
                                                .length ==
                                            findFormHierarOrgEle(
                                                form.custom_hirarchy_with_elements,
                                                hierarchy.id
                                            ).length
                                        "
                                    />
                                    <p class="text-[#636363] text-16">
                                        {{ $t("All") }}
                                    </p>
                                </div>
                                <div
                                    class="select-none flex gap-[10px] items-center cursor-pointer"
                                    @click="
                                        () =>
                                            findFormHierarOrgEle(
                                                form.custom_hirarchy_with_elements,
                                                hierarchy.id
                                            ).length
                                                ? deselectAll(
                                                      form.custom_hirarchy_with_elements,
                                                      hierarchy.id
                                                  )
                                                : null
                                    "
                                >
                                    <Checkbox
                                        :checked="
                                            findFormHierarOrgEle(
                                                form.custom_hirarchy_with_elements,
                                                hierarchy.id
                                            ).length
                                        "
                                    />
                                    <p
                                        class="text-[#636363] text-16"
                                        :class="{
                                            'text-[#ADB5BD]':
                                                !findFormHierarOrgEle(
                                                    form.custom_hirarchy_with_elements,
                                                    hierarchy.id
                                                ).length,
                                        }"
                                    >
                                        {{ $t("None") }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </MultiSelect>
                </template>

                <!----None hierarcy by access right custom---->
                <template
                    v-if="
                        none_lavel_hierarchy_elements.length &&
                        form.access_right == 3 &&
                        (form.roles_id == 1 || form.roles_id == 2)
                    "
                >
                    <MultiSelect
                        valueKey="id"
                        labelKey="name"
                        v-for="(
                            none_hierarchy, index
                        ) in none_lavel_hierarchy_elements"
                        :key="index"
                        :options="none_hierarchy.organization_elements"
                        :label="none_hierarchy.name"
                        :topLabel="
                            hierarchy_elements.length == 0 && index == 0
                                ? true
                                : false
                        "
                        :hierarchyCustom="true"
                        v-model="
                            form.custom_none_hirarchy.find(
                                ({ hierarchy_id }) =>
                                    hierarchy_id == none_hierarchy.id
                            ).organization_elements
                        "
                        :placeholder="`${none_hierarchy.name} ${$t('Select')}`"
                        :asterisk="true"
                        @onUpdate="
                            (value) => {
                                if (value.length > 0) {
                                    return form.errors.clear(
                                        `custom_none_hirarchy.${index}`
                                    );
                                }
                            }
                        "
                        :error="
                            form.errors.has(`custom_none_hirarchy.${index}`)
                        "
                    >
                        <template #options-top="{ search }">
                            <div class="gap-[10px]">
                                <div
                                    class="select-all flex gap-[10px] items-center cursor-pointer pb-[10px]"
                                    @click="
                                        () =>
                                            none_hierarchy.organization_elements
                                                .length !==
                                            findFormHierarOrgEle(
                                                form.custom_none_hirarchy,
                                                none_hierarchy.id
                                            ).length
                                                ? selectAll(
                                                      form.custom_none_hirarchy,
                                                      none_lavel_hierarchy_elements,
                                                      none_hierarchy.id
                                                  )
                                                : null
                                    "
                                >
                                    <Checkbox
                                        :checked="
                                            none_hierarchy.organization_elements
                                                .length ==
                                            findFormHierarOrgEle(
                                                form.custom_none_hirarchy,
                                                none_hierarchy.id
                                            ).length
                                        "
                                    />
                                    <p class="text-[#636363] text-16">
                                        {{ $t("All") }}
                                    </p>
                                </div>
                                <div
                                    class="select-none flex gap-[10px] items-center cursor-pointer"
                                    @click="
                                        () =>
                                            findFormHierarOrgEle(
                                                form.custom_none_hirarchy,
                                                none_hierarchy.id
                                            ).length
                                                ? deselectAll(
                                                      form.custom_none_hirarchy,
                                                      none_hierarchy.id
                                                  )
                                                : null
                                    "
                                >
                                    <Checkbox
                                        :checked="
                                            findFormHierarOrgEle(
                                                form.custom_none_hirarchy,
                                                none_hierarchy.id
                                            ).length
                                        "
                                    />
                                    <p
                                        class="text-[#636363] text-16"
                                        :class="{
                                            'text-[#ADB5BD]':
                                                !findFormHierarOrgEle(
                                                    form.custom_none_hirarchy,
                                                    none_hierarchy.id
                                                ).length,
                                        }"
                                    >
                                        {{ $t("None") }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </MultiSelect>
                </template>
                <!------------Allignemnt------------->
                <MultiSelect
                    v-if="form.roles_id == 3 || form.roles_id == 4"
                    :options="allignment"
                    v-model="form.alligment"
                    label="Allignment"
                    placeholder="Select allignment"
                    labelClass="whitespace-nowrap text-[18px] font-semibold"
                    :asterisk="true"
                    :error="form.errors.has('alignment')"
                    @onUpdate="
                        (value) => {
                            if (value.length > 0) {
                                return form.errors.clear('alignment');
                            }
                        }
                    "
                />
                <!------------Correspondence language------------->
                <SingleLanguageSelect
                    label="Correspondence Language"
                    placeholder="Correspondence language select"
                    labelClass="whitespace-nowrap"
                    :asterisk="true"
                    :searchable="false"
                    v-model="form.correspondence_language_code"
                    :error="v$.correspondence_language_code.$errors.length > 0"
                />
                <!------------System language------------->
                <SingleSelect
                    label="System language"
                    placeholder="Select language"
                    asterisk="true"
                    :options="formatedLanguage"
                    v-model="form.language_id"
                    optionsClass="w-full right-0"
                    :error="v$.language_id.$errors.length > 0"
                />
            </div>

            <!-- Third Row -->
            <div class="spacer mt-[35px] col-span-3"></div>

            <!------------Salutation------------->
            <SingleSelect
                label="Salutation"
                placeholder="Select salutation"
                asterisk="true"
                :options="salutations"
                v-model="form.salutation"
                optionsClass="w-full right-0"
                :error="v$.salutation.$errors.length > 0"
            />
            <!------------First name------------->
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
            <!------------Last name------------->
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

            <!-- Fourth Row -->
            <div class="spacer col-span-3 mt-[35px]"></div>

            <!------------Email address------------->
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
            <!------------Phone number------------->
            <PhoneNumberInput
                label="Telefonnummer"
                v-model:country_code="form.phone_iso_code"
                v-model:phone_number="form.phone_number"
            />
            <div class="spacer col-span-3 mb-[80px]"></div>

            <div
                class="flex flex-col"
                v-if="form.roles_id == 3 || form.roles_id == 4"
            >
                <h2
                    class="label text-formLabel font-inter text-input mb-[30px] text-[20px]"
                >
                    {{ $t("Competencies") + "*" }}
                </h2>
                <ButtonGradient
                    class="w-[286px] flex justify-center items-center gap-4"
                    type="button"
                    @click="addCompetencies"
                >
                    <PlusIcon />
                    {{ $t("Add competence") }}
                </ButtonGradient>
            </div>

            <div class="spacer mt-[25px] col-span-3"></div>

            <CompetenceList
                v-if="
                    form.competence?.length &&
                    (form.roles_id == 3 || form.roles_id == 4)
                "
                :competences="form.competence"
                :edit="true"
                @onUpdate="
                    (competence, index) => {
                        form.competence[index] = competence;
                        updateCompetence(competence, index);
                    }
                "
                @onDelete="
                    (competence, index) => {
                        deleteCompetence(competence, index);
                    }
                "
            />
            <div class="spacer mt-[100px] col-span-3"></div>
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
                            title: 'Discard changes?',
                            description: `If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?`,
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
import RadioInput from "@/components/form/RadioInputs.vue";
import Checkbox from "@/components/utils/Checkbox.vue";
import PhoneNumberInput from "@/components/form/PhoneNumberInput.vue";
import { reactive, computed, ref } from "@vue/reactivity";
import SingleSelect from "@/components/form/SingleSelect.vue";
import SingleSelectHierarchy from "./components/SingleSelect.vue";
import MultiSelect from "./components/MultiSelect.vue";
import MultiSelectHierarchy from "./components/MultiSelectHierarchy.vue";
import MultiSelectNoneHierarcy from "./components/MultiSelectNoneHierarcy.vue";
import PlusIcon from "@/components/icons/Plus.vue";
import CountrySelect from "@/components/form/CountrySelect.vue";
import { useRoute, useRouter } from "vue-router";
import { companyRolesStore } from "@/store/company_roles.js";
import { notificationShowStore } from "@/store/notification.js";
import { languageStore } from "@/store/language.js";
import SingleLanguageSelect from "./components/SingleLanguageSelect.vue";
import CompetenceModal from "./components/CompetenceModal.vue";
import CompetenceList from "./components/CompetenceList.vue";
import ToggleSwitch from "@/components/form/ToggleSwitch.vue";
import Back from "@/components/form/Back.vue";
import { useIsEqual } from "@/composables/utils.js";
import {
    inject,
    onBeforeMount,
    onMounted,
    onBeforeUpdate,
    onUpdated,
    onBeforeUnmount,
    onUnmounted,
    onActivated,
    onDeactivated,
    onErrorCaptured,
    watch,
} from "@vue/runtime-core";

import { useVuelidate } from "@vuelidate/core";
import { helpers, email, maxLength, required } from "@vuelidate/validators";
import axios from "axios";
import { trans } from "laravel-vue-i18n";
import { storeToRefs } from "pinia";
import Form from "vform";
import accessRight from "@/access_right.json";
import allignment from "@/allignment.json";
import { useUserStore } from "@/store/user";
import languagesPlugin from "@cospired/i18n-iso-languages";

const { user } = useUserStore();
const { formatedRoles } = storeToRefs(companyRolesStore());
const { formatedLanguage, defaultLanguage } = storeToRefs(languageStore());
const notificationSend = notificationShowStore();
const hierarchy_elements = ref([]);
const none_lavel_hierarchy_elements = ref([]);
const hierarchy_elements_with_parent = ref([]);
const vueRoute = useRoute();

const prefix_id = ref("");
const salutations = [
    { value: "Ms", label: "Ms" },
    { value: "Mr", label: "Mr" },
    { value: "/", label: "/" },
];

let campaignList = ref([]);
const internal_user = reactive({});
const router = useRouter();
const $vfm = inject("$vfm");
const form = reactive(
    new Form({
        alligment: [],
        access_right: "",
        campaign_id: [],
        correspondence_language_code: null,
        competence: [],
        custom_hirarchy_with_elements: [],
        custom_none_hirarchy: [],
        email: "",
        frist_name: "",
        hirarchy_with_elements: [],
        internal_user_id: "",
        language_id: "",
        last_name: "",
        none_hirarchy: [],
        phone_iso_code: "",
        phone_number: "",
        roles_id: "",
        salutation: "",
        send_mail: false,
        user_id: "",
    })
);
watch(
    () => form.roles_id,
    () => {
        getHierarchy();
    }
);

const addCompetencies = () => {
    const options = {
        component: CompetenceModal,
        bind: {
            title: trans("Add new competence"),
        },
        on: {
            saveElement: (element) => {
                form.competence.push(element);
                notificationSend.success(
                    "The competence/s has/have been successfully recorded."
                );
            },
        },
    };
    $vfm.show(options);
};
const updateCompetence = (competences, index) => {
    notificationSend.success("The competence was successfully updated.");
};
const deleteCompetence = (competences, index) => {
    form.competence.splice(index, 1);
    if (competences.id) {
        deleteCompetenceBackend(competences.id);
    }
    notificationSend.success(
        trans("Delete competence success “:competence“", {
            competence: competences.lang_code
                ? languagesPlugin.getName(
                      competences.lang_code,
                      user.language.code
                  )
                : competences.other_competence,
        })
    );
};

const selectAll = (object, hierarchy_element, hierachy_id) => {
    object.find(
        (item) => item.hierarchy_id == hierachy_id
    ).organization_elements = hierarchy_element
        .find((item) => item.id == hierachy_id)
        .organization_elements.map(({ id }) => id);
};

const deselectAll = (object, hierachy_id) => {
    object.find(
        (item) => item.hierarchy_id == hierachy_id
    ).organization_elements = [];
};

const findFormHierarOrgEle = (object, none_hierarchy_id) => {
    return object.find(({ hierarchy_id }) => hierarchy_id == none_hierarchy_id)
        .organization_elements;
};

const getCampaignList = async () => {
    try {
        const res = await axios.get(route("campaign.index"));
        const campaign = res.data;
        campaignList.value = campaign.map(({ id: value, name: label }) => ({
            value,
            label,
        }));
        if (campaignList.value.length == 1) {
            form.campaign_id = [
                ...form.campaign_id,
                campaignList.value[0].value,
            ];
        }
    } catch (error) {
        console.log(error);
    }
};

const getHierarchy = async () => {
    try {
        const res = await axios.get(
            route("hierarchy.organization-elements", { role_id: form.roles_id })
        );
        form.hirarchy_with_elements = [];
        form.custom_hirarchy_with_elements = [];
        hierarchy_elements.value = res.data;
        let topHeirarchy = [];

        if (form.roles_id == 1 || form.roles_id == 2) {
            form.alligment = [];
        }
        if (form.roles_id == 3 || form.roles_id == 4) {
            form.access_right = "";
        }

        Object.values(res.data).forEach((item) => {
            const responsible = item.responsible_roles.find(
                (responsible) => (responsible.company_role_id = form.roles_id)
            );
            const direct_subordinate_roles = item.direct_subordinate_roles.find(
                (direct_subordinate_roles_item) =>
                    (direct_subordinate_roles_item.company_role_id =
                        form.roles_id)
            );
            form.hirarchy_with_elements.push({
                hierarchy_id: item.id,
                organization_elements: "",
                responsible: responsible,
                direct_subordinate_roles: direct_subordinate_roles,
            });

            form.custom_hirarchy_with_elements.push({
                hierarchy_id: item.id,
                organization_elements: [],
                responsible: responsible,
                direct_subordinate_roles: direct_subordinate_roles,
            });
        });

        // on edit set organization_elements
        if (form.hirarchy_with_elements.length > 0) {
            const topHierarchyId = form.hirarchy_with_elements[0].hierarchy_id;
            topHeirarchy = internal_user.user.organization_element_user.find(
                ({ organization_element }) =>
                    (organization_element.type_id = topHierarchyId)
            );

            if (topHeirarchy) {
                let oraganization_element = hierarchy_elements.value
                    .find(
                        ({ id }) =>
                            topHeirarchy.organization_element.type_id == id
                    )
                    .organization_elements.find(({ id, type_id }) => {
                        return (
                            id == topHeirarchy.organization_element.id &&
                            type_id == topHierarchyId
                        );
                    });

                form.hirarchy_with_elements.find(
                    ({ hierarchy_id }) =>
                        topHeirarchy.organization_element.type_id ==
                        hierarchy_id
                ).organization_elements = oraganization_element
                    ? oraganization_element.id
                    : "";
            }

            form.originalData["hirarchy_with_elements"] =
                form.hirarchy_with_elements;
        }

        // custom hierarchy elements set
        Object.values(form.custom_hirarchy_with_elements).forEach((item) => {
            internal_user.user.custom_organization_element_user
                .filter(
                    ({ organization_element }) =>
                        organization_element.type_id == item.hierarchy_id
                )
                .forEach(({ organization_element_id }) =>
                    item.organization_elements.push(organization_element_id)
                );
        });

        form.originalData["custom_hirarchy_with_elements"] =
            form.custom_hirarchy_with_elements;

        if (res.data.length) {
            const hierachy_id = res.data[0].id;
            const hierarchy_element = await axios.get(
                route("hierarchy.organization-elements-with-parent", {
                    hierachy_id: hierachy_id,
                })
            );
            hierarchy_elements_with_parent.value = hierarchy_element.data;

            //on edit set parent organization element
            if (topHeirarchy)
                hierachy_parent_select(
                    topHeirarchy.organization_element.id,
                    ""
                );
        }
    } catch (error) {
        console.log(error);
    }
};
const getNoneLavelHierarchy = async () => {
    try {
        const res = await axios.get(
            route("hierarchy-lavel-none.organization-elements", {})
        );
        none_lavel_hierarchy_elements.value = res.data;

        Object.values(res.data).forEach((item) => {
            const responsible = item.responsible_roles.find(
                (responsible) => (responsible.company_role_id = form.roles_id)
            );
            const direct_subordinate_roles = item.direct_subordinate_roles.find(
                (direct_subordinate_roles_item) =>
                    (direct_subordinate_roles_item.company_role_id =
                        form.roles_id)
            );
            form.none_hirarchy.push({
                hierarchy_id: item.id,
                organization_elements: [],
                responsible: responsible,
                direct_subordinate_roles: direct_subordinate_roles,
            });
            form.custom_none_hirarchy.push({
                hierarchy_id: item.id,
                organization_elements: [],
                responsible: responsible,
                direct_subordinate_roles: direct_subordinate_roles,
            });
        });
    } catch (error) {
        console.log(error);
    }
};

const specific_hierarchy_element_find = (hierarchy_element_id) => {
    return hierarchy_elements_with_parent.value.find(
        (hierarchy_element) => hierarchy_element.id == hierarchy_element_id
    );
};

//set parent hierarchy organization element
const hierachy_parent_select = (hierachy_element_id, top_hierarchy_id) => {
    // get al other hierarchy info by top hierarchy level option_id
    const hierarchy_element =
        specific_hierarchy_element_find(hierachy_element_id);

    if (typeof hierarchy_element !== "undefined") {
        hierarchy_element.parent_organization_elements.forEach(
            (top_hierarchy_parent_element) => {
                // parent hierarchy element unchecked by top hierarchy element
                const form_hierarchy_element_parent =
                    form.hirarchy_with_elements.find(
                        ({ hierarchy_id }) =>
                            hierarchy_id == top_hierarchy_parent_element.type_id
                    );
                form_hierarchy_element_parent.organization_elements =
                    top_hierarchy_parent_element.id;
            }
        );
    }
};

const validateHierarchyElement = (value, type) => {
    let error = false;
    value.forEach(({ hierarchy_id, organization_elements }, index) => {
        if (organization_elements.length == 0) {
            let hierarchy = "";
            if (type == "hirarchy_with_elements") {
                hierarchy = `hirarchy_with_elements.${index}`;
            } else if (type == "none_hirarchy_with_elements") {
                hierarchy = `none_hirarchy_with_elements.${index}`;
            } else if (type == "custom_hirarchy_with_elements") {
                hierarchy = `custom_hirarchy_with_elements.${index}`;
            } else if (type == "custom_none_hirarchy") {
                hierarchy = `custom_none_hirarchy.${index}`;
            }
            form.errors.set(hierarchy, "Field Missing");
            error = true;
        }
    });

    return error;
};
const validateNoneHierarchyElement = (value) => {
    let error = false;
    value.forEach(({ hierarchy_id, organization_elements }, index) => {
        if (organization_elements.length == 0) {
            form.errors.set(
                `none_hirarchy_with_elements.${index}`,
                "Field Missing"
            );
            error = true;
        }
    });

    return error;
};
// Alignment required check when roles 3 and 4
const validateAlignment = () => {
    let error = false;

    if (
        (form.roles_id == 3 || form.roles_id == 4) &&
        form.alligment.length == 0
    ) {
        form.errors.set(`alignment`, "Field Missing");
        error = true;
    }
    return error;
};

// Access right required check when roles 1 and 2
const validateAcessRight = () => {
    let error = false;
    if (
        (form.roles_id == 1 || form.roles_id == 2) &&
        form.access_right.length == 0
    ) {
        form.errors.set(`access_right`, "Field Missing");
        error = true;
    }
    return error;
};

// Competence required check when roles 3 and 4
const validateCompetence = () => {
    let errorCompetence = false;

    if (
        (form.roles_id == 3 || form.roles_id == 4) &&
        form.competence.length == 0
    ) {
        errorCompetence = true;
    }

    return errorCompetence;
};

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
    roles_id: {
        required: helpers.withMessage("", required),
    },
    language_id: {
        required: helpers.withMessage("", required),
    },
    correspondence_language_code: {
        required: helpers.withMessage("", required),
    },
    salutation: {
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
    console.log(form);
    const isValidHierarchyOrganizattionElement = validateHierarchyElement(
        form.hirarchy_with_elements,
        "hirarchy_with_elements"
    );
    const isValidNoneHierarchyOrgElem = validateHierarchyElement(
        form.none_hirarchy,
        "none_hirarchy_with_elements"
    );

    let isValidCustomHierarchyOrgElem =
        form.access_right == 3 && (form.roles_id == 1 || form.roles_id == 2)
            ? validateHierarchyElement(
                  form.custom_hirarchy_with_elements,
                  "custom_hirarchy_with_elements"
              )
            : false;

    let isValidCustomNoneHierarchyOrgElem =
        form.access_right == 3 && (form.roles_id == 1 || form.roles_id == 2)
            ? validateHierarchyElement(
                  form.custom_none_hirarchy,
                  "custom_none_hirarchy"
              )
            : false;

    const isAlignment = validateAlignment();
    const isAcessRight = validateAcessRight();
    const isCompetence = validateCompetence();

    if (
        v$.value.$invalid ||
        isValidHierarchyOrganizattionElement ||
        isValidNoneHierarchyOrgElem ||
        isAlignment ||
        isAcessRight ||
        isValidCustomHierarchyOrgElem ||
        isValidCustomNoneHierarchyOrgElem ||
        isCompetence
    ) {
        $vfm.show("field-missing");
        return;
    }
    try {
        const { data } = await form.put(
            route("internal-users.update", {
                internal_user: form.internal_user_id,
            }),
            { form }
        );
        notificationSend.success(
            {
                text:
                    data.change_mail == true
                        ? "The internal user :name was successfully updated and an email address verification email sent."
                        : "The internal user :name was successfully updated.",
                replace: { name: data.name },
            },
            trans("Notification")
        );
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

const deleteCompetenceBackend = async (competence_id) => {
    try {
        await axios.delete(
            route("competence.destroy", {
                competence: competence_id,
            })
        );
    } catch (error) {
        console.log(error);
        if (error) {
            $vfm.show("field-missing");
        }
    }
};

const getInternalUserData = async () => {
    try {
        const { data } = await axios.get(
            route("internal-users.show", { internal_user: vueRoute.params.id })
        );

        Object.assign(internal_user, data);
        form.user_id = internal_user.user_id;
        form.internal_user_id = internal_user.id;
        if (data.user.competence) {
            form.competence = data.user.competence;
        }

        form.roles_id = internal_user.roles_id;
        form.first_name = internal_user.user.first_name;
        form.last_name = internal_user.user.last_name;
        form.salutation = internal_user.salutation;
        form.phone_iso_code = internal_user.phone_iso_code ?? "";
        form.phone_number = internal_user.phone_number ?? "";
        form.email = internal_user.user.email;
        if (internal_user.access_right) {
            form.access_right = internal_user.access_right.toString();
        }

        email.value = internal_user.user.email;

        //alignment set
        internal_user.user.alignment_user.forEach((item) => {
            form.alligment = [...form.alligment, item.alignment_id];
        });

        //campaign
        Object.values(internal_user.user.campaign_internal).forEach((item) => {
            form.campaign_id = [...form.campaign_id, item.campaign_id];
        });

        // none hierarchy elements set
        Object.values(form.none_hirarchy).forEach((item) => {
            internal_user.user.organization_element_user
                .filter(
                    ({ organization_element }) =>
                        organization_element.type_id == item.hierarchy_id
                )
                .forEach(
                    ({ organization_element_id }) =>
                        (item.organization_elements = [
                            ...item.organization_elements,
                            organization_element_id,
                        ])
                );
        });

        // custom_none_hirarchy hierarchy elements set
        Object.values(form.custom_none_hirarchy).forEach((item) => {
            internal_user.user.custom_organization_element_user
                .filter(
                    ({ organization_element }) =>
                        organization_element.type_id == item.hierarchy_id
                )
                .forEach(
                    ({ organization_element_id }) =>
                        (item.organization_elements = [
                            ...item.organization_elements,
                            organization_element_id,
                        ])
                );
        });

        form.correspondence_language_code =
            internal_user.correspondence_language_code;

        form.originalData = form.data();
    } catch (error) {
        console.log(error);
        // router.push({ name: '404' })
    }
};
getCampaignList();
getNoneLavelHierarchy();

onBeforeMount(async () => {
    await getInternalUserData();
    form.language_id = form.originalData.language_id = defaultLanguage.value.id;
    if (
        internal_user.access_right &&
        (form.roles_id == 1 || form.roles_id == 2)
    ) {
        form.access_right = internal_user.access_right.toString();
    }
});
</script>

<style scoped></style>

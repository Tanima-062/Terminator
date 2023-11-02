<template>
    <div class="content px-6">
        <div
            class="bg-white shadow-xl shadow-#1f2937-800/25 px-[42px] py-[43px] overflow-y-auto pr-[127px] rounded-[15px]"
        >
            <Back class="mb-[33px]" />

            <div class="mb-[40px] xs:mb-[20px]">
                <h1 class="text-formHeading text-title font-poppins mb-[60px]">
                    {{ $t("Internal user detail") }}
                </h1>
            </div>
            <template v-if="internal_user">
                <div class="flex">
                    <div class="w-1/3">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Creation Date") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                            v-date-format="internal_user.created_at"
                        ></p>
                    </div>

                    <div class="w-1/3">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("User ID") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ internal_user.prefix_id }}
                        </p>
                    </div>

                    <div class="w-1/3">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Role") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ $t(`${internal_user.company_role.name}`) }}
                        </p>
                    </div>
                </div>

                <div class="spacer mt-[45px] col-span-3"></div>

                <div class="flex">
                    <div class="w-1/3">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Campaign") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ campaign }}
                        </p>
                    </div>

                    <div class="w-1/3" v-if="device_auth_roles == true">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Device Authentication required?") }}
                        </h2>
                        <div
                            class="text-value text-4 font-[400px] font-inter leading-[19px] flex"
                        >
                            <div v-if="device_auth_roles" class="flex gap-4">
                                <div class="flex">
                                    <div class="mt-1">
                                        <span>
                                            <svg
                                                width="15"
                                                height="15"
                                                viewBox="0 0 15 15"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <rect
                                                    x="0.5"
                                                    y="0.5"
                                                    width="14"
                                                    height="14"
                                                    rx="7"
                                                    stroke="#AB326F"
                                                />
                                                <circle
                                                    cx="7.5"
                                                    cy="7.5"
                                                    r="4.5"
                                                    fill="#AB326F"
                                                />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-1 mt-[.50px]">Yes</div>
                                </div>
                                <div class="flex">
                                    <div class="mt-1">
                                        <span>
                                            <svg
                                                width="15"
                                                height="15"
                                                viewBox="0 0 15 15"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <rect
                                                    x="0.5"
                                                    y="0.5"
                                                    width="14"
                                                    height="14"
                                                    rx="7"
                                                    stroke="#676767"
                                                />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-1 mt-[.50px]">No</div>
                                </div>
                            </div>
                            <div
                                class="flex gap-4"
                                v-if="device_auth_roles == false"
                            >
                                <div class="flex">
                                    <div class="mt-1">
                                        <span>
                                            <svg
                                                width="15"
                                                height="15"
                                                viewBox="0 0 15 15"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <rect
                                                    x="0.5"
                                                    y="0.5"
                                                    width="14"
                                                    height="14"
                                                    rx="7"
                                                    stroke="#676767"
                                                />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-1 mt-[.50px]">Yes</div>
                                </div>
                                <div class="mt-1">
                                    <span>
                                        <svg
                                            width="15"
                                            height="15"
                                            viewBox="0 0 15 15"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <rect
                                                x="0.5"
                                                y="0.5"
                                                width="14"
                                                height="14"
                                                rx="7"
                                                stroke="#AB326F"
                                            />
                                            <circle
                                                cx="7.5"
                                                cy="7.5"
                                                r="4.5"
                                                fill="#AB326F"
                                            />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-1 mt-[.50px]">No</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="spacer mt-[45px] col-span-3"></div>

                <div class="grid grid-cols-3 mb-10">
                    <!-- Hierarchy oraganization eleemnt -->
                    <div
                        class="mb-[45px]"
                        v-if="herarchy_with_elelemnt?.organization_element"
                    >
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{
                                herarchy_with_elelemnt.organization_element
                                    .hierarchy_type.name
                            }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{
                                herarchy_with_elelemnt.organization_element.name
                            }}
                        </p>
                    </div>

                    <!-- Parent Hierarchy oraganization eleemnt -->
                    <template
                        v-if="
                            herarchy_with_elelemnt?.organization_element
                                .parent_organization_elements
                        "
                    >
                        <div
                            class="mb-[45px]"
                            v-for="(
                                parent_hierachy, index
                            ) in herarchy_with_elelemnt.organization_element
                                .parent_organization_elements"
                            :key="index"
                            index
                        >
                            <h2
                                class="label text-formLabel font-inter text-input mb-[10px]"
                            >
                                {{ parent_hierachy.hierarchy_type.name }}
                            </h2>
                            <p
                                class="text-value text-4 font-[400px] font-inter leading-[19px]"
                            >
                                {{ parent_hierachy.name }}
                            </p>
                        </div>
                    </template>

                    <!-- none Hierarchy oraganization eleemnt -->
                    <template v-if="none_hierarchy_element_process.length">
                        <div
                            class="mb-[45px]"
                            v-for="(
                                none_hierarchy_info, index
                            ) in none_hierarchy_element_process"
                            :key="index"
                            index
                        >
                            <h2
                                class="label text-formLabel font-inter text-input mb-[10px]"
                            >
                                {{ none_hierarchy_info.hierarchy_name }}
                            </h2>
                            <p
                                class="text-value text-4 font-[400px] font-inter leading-[19px]"
                            >
                                {{ none_hierarchy_info.organization_elements }}
                            </p>
                        </div>
                    </template>

                    <!-- alignemnt -->
                    <div
                        class="alignemnt"
                        v-if="
                            internal_user.roles_id == 3 ||
                            internal_user.roles_id == 4
                        "
                    >
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Allignment") }}
                        </h2>

                        <div
                            class="text-value text-4 font-[400px] font-inter leading-[19px] flex"
                        >
                            <div
                                class="flex ml-2"
                                v-for="(item, index) in allignment_process"
                                :key="index"
                            >
                                <div class="mt-1">
                                    <span>
                                        <svg
                                            width="15"
                                            height="15"
                                            viewBox="0 0 15 15"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <rect
                                                x="0.5"
                                                y="0.5"
                                                width="14"
                                                height="14"
                                                rx="7"
                                                stroke="#AB326F"
                                            />
                                            <circle
                                                cx="7.5"
                                                cy="7.5"
                                                r="4.5"
                                                fill="#AB326F"
                                            />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-1 mt-[.50px]">
                                    {{ item }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Correspondence Language -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Correspondence Language") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{
                                languagesPlugin.getName(
                                    internal_user?.user?.language?.code,
                                    user.language.code
                                )
                            }}
                        </p>
                    </div>

                    <!-- System language -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("System language") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{
                                languagesPlugin.getName(
                                    internal_user.correspondence_language_code,
                                    user.language.code
                                )
                            }}
                        </p>
                    </div>
                    <!-- Salutation -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Salutation") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ $t(`${internal_user.salutation}`) }}
                        </p>
                    </div>
                    <!-- First name -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("First Name") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ internal_user.user.first_name }}
                        </p>
                    </div>
                    <!-- Last name -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Last Name") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ internal_user.user.last_name }}
                        </p>
                    </div>
                    <!-- Email -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Email") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ internal_user.user.email }}
                        </p>
                    </div>
                    <!-- Phone Number -->
                    <div class="mb-[45px]">
                        <h2
                            class="label text-formLabel font-inter text-input mb-[10px]"
                        >
                            {{ $t("Phone Number") }}
                        </h2>
                        <p
                            class="text-value text-4 font-[400px] font-inter leading-[19px]"
                        >
                            {{ internal_user.full_phone_number }}
                        </p>
                    </div>
                </div>

                <CompetenceListDetails
                    v-if="
                        internal_user.user.competence.length &&
                        (internal_user.roles_id == 3 ||
                            internal_user.roles_id == 4)
                    "
                    :competences="internal_user.user.competence"
                    :show="false"
                />

                <div class="spacer mt-[80px] col-span-3"></div>

                <div class="relative">
                    <router-link
                        :to="{
                            name: 'internal-user-edit',
                            params: { id: $route.params.id },
                        }"
                    >
                        <ButtonGradient class="w-[198px]" type="submit">
                            <PencilIcon class="absolute" style="left: 3%" />
                            <span class="m-auto pl-8 align-middle">{{
                                $t("Edit")
                            }}</span>
                        </ButtonGradient>
                    </router-link>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import ButtonGradient from "@/components/button/Gradient.vue";
import CompetenceListDetails from "./components/CompetenceListDetails.vue";
import PencilIcon from "@/components/icons/PencilIcon.vue";
import Back from "@/components/form/Back.vue";
import { useRoute, useRouter } from "vue-router";
import { ref, computed, reactive } from "@vue/reactivity";
import axios from "axios";
import { onMounted, inject } from "@vue/runtime-core";
import allignmentOptionList from "@/allignment.json";
import languagesPlugin from "@cospired/i18n-iso-languages";
import { useUserStore } from "@/store/user.js";
import { storeToRefs } from "pinia";

const vueRoute = useRoute();
const router = useRouter();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const internal_user = ref();
let campaignList = ref("");
let device_auth_roles = false;
let alignments = reactive([]);
let organization_element_user = reactive([]);
let none_hirarchy = [];
let none_hierarchy_with_orga_ele = reactive([]);

const getInternalUserDetails = async () => {
    try {
        const { data } = await axios.get(
            route("internal-users.show", { internal_user: vueRoute.params.id })
        );
        internal_user.value = await data;
        device_auth_roles =
            internal_user.value.user?.company?.device_auth_roles.length > 0 &&
            internal_user.value.user?.company?.device_authentication_required ==
                true
                ? true
                : false;
    } catch (error) {
        router.push({ name: "404" });
    }
};

const campaign = computed(() => {
    campaignList =
        internal_user.value?.user?.campaign_internal.length > 0
            ? Object.values(internal_user.value.user.campaign_internal)
                  .map((value, index) => {
                      return value.campaign.name;
                  })
                  .join(", ")
            : "";
    return campaignList;
});

const allignment_process = computed(() => {
    alignments =
        internal_user.value?.user?.alignment_user.length > 0
            ? Object.values(internal_user.value.user.alignment_user).map(
                  (storedAlignment, index) => {
                      return allignmentOptionList.find(
                          (item) => item.value == storedAlignment.alignment_id
                      ).label;
                  }
              )
            : [];
    return alignments;
});

const herarchy_with_elelemnt = computed(() => {
    return internal_user.value.user?.organization_element_user
        ? internal_user.value.user?.organization_element_user.find(
              ({ organization_element }) => {
                  return (
                      organization_element.hierarchy_type.hierarchy_level !==
                      null
                  );
              }
          )
        : [];
});

const none_hierarchy_element_process = computed(() => {
    if (internal_user.value.user?.organization_element_user) {
        // separate hierarchy with empty organization_element
        internal_user.value.user?.organization_element_user
            .filter(({ organization_element }) => {
                return (
                    organization_element.hierarchy_type.hierarchy_level == null
                );
            })
            .forEach((organization_info) => {
                organization_info.organization_element.hierarchy_type.id;
                if (!none_hirarchy.length) {
                    none_hirarchy = [
                        ...none_hirarchy,
                        {
                            hierarchy_id:
                                organization_info.organization_element
                                    .hierarchy_type.id,
                            hierarchy_name:
                                organization_info.organization_element
                                    .hierarchy_type.name,
                            organization_elements: [],
                        },
                    ];
                }

                if (
                    none_hirarchy.length &&
                    !none_hirarchy.find((exitsHierarchy) => {
                        return (
                            exitsHierarchy.hierarchy_id ===
                            organization_info.organization_element
                                .hierarchy_type.id
                        );
                    })
                ) {
                    none_hirarchy = [
                        ...none_hirarchy,
                        {
                            hierarchy_id:
                                organization_info.organization_element
                                    .hierarchy_type.id,
                            hierarchy_name:
                                organization_info.organization_element
                                    .hierarchy_type.name,
                            organization_elements: "",
                        },
                    ];
                }
            });

        //join organization_element as hierarchy
        none_hirarchy.forEach((exitsHierarchy) => {
            let oraganizationObject =
                internal_user.value.user?.organization_element_user.filter(
                    ({ organization_element }) => {
                        return (
                            organization_element.hierarchy_type.id ==
                            exitsHierarchy.hierarchy_id
                        );
                    }
                );

            const org_ele = oraganizationObject
                .map((organization_info) => {
                    return organization_info.organization_element.name;
                })
                .join(", ");
            exitsHierarchy.organization_elements = org_ele;
        });
    }

    return none_hirarchy;
});
getInternalUserDetails();
</script>

<style lang="scss" scoped></style>

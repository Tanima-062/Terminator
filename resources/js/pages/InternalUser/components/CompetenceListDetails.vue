<template>
    <div
        class="organization-competences w-[950px] flex flex-col col-span-3 mt-5"
    >
        <h1 class="text-[20px] text-[#555555] font-semibold font-inter pb-5">
            {{ $t("Competencies") }}
        </h1>
        <div
            class="flex w-full min-h-[31px] items-center pr-[22px] bg-[#AB326F] rounded-t-[4px] pl-[25px] plr-[10px]"
        >
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[35%]"
                style="color: #ffffff"
            >
                {{ $t("Creation Date") }}
            </div>
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[40%]"
                style="color: #ffffff"
            >
                {{ $t("Art") }}
            </div>
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[30%]"
                style="color: #ffffff"
            >
                {{ $t("Competence") }}
            </div>
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[20%]"
                style="color: #ffffff"
            >
                {{ $t("Label") }}
            </div>
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[32%]"
                style="color: #ffffff"
            >
                {{ $t("Competence status") }}
            </div>
            <div
                class="text-[16px] leading-[19px] text-[#292929] w-[18%]"
                style="color: #ffffff"
            ></div>
        </div>

        <div
            class="competences divide-y divide-input border-b-[1px] border-x-[1px] border-[#E6DEE5]"
        >
            <div
                class="flex justify-center pl-[25px] pr-[15px] min-h-[45px] items-center"
                v-for="(competence, index) in competences"
                :key="index"
            >
                <div
                    class="td w-[20%]"
                    v-date-format="competence.created_at"
                ></div>
                <div class="td w-[24%]">
                    {{
                        competence.type == "language"
                            ? $t("Language")
                            : $t("Other competence")
                    }}
                </div>
                <div class="td w-[18%]">
                    {{
                        competence.lang_code
                            ? languagesPlugin.getName(
                                  competence.lang_code,
                                  user.language.code
                              )
                            : competence.other_competence
                    }}
                </div>
                <div class="td w-[12%]">{{ competence.level }}</div>
                <div class="td w-[18%]">
                    {{
                        competence.status == "confirmed"
                            ? $t("Confirmed")
                            : $t("Not confirmed")
                    }}
                </div>
                <div class="td w-[12%] flex justify-end">
                    <MenuBarCompetence
                        :competence_log="competence.competence_log"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import PencilBoxIcon from "@/components/icons/PencilBoxIcon.vue";
import DeleteBoxIcon from "@/components/icons/DeleteBoxIcon.vue";
import { trans } from "laravel-vue-i18n";
import { inject } from "@vue/runtime-core";
import { computed, reactive } from "@vue/reactivity";
import { notificationShowStore } from "@/store/notification.js";
import languagesPlugin from "@cospired/i18n-iso-languages";
import language from "@/language.json";
import { useUserStore } from "@/store/user";
import CompetenceModal from "../components/CompetenceModal.vue";
import MenuBarCompetence from "../components/MenuBarCompetence.vue";

const props = defineProps({
    competences: {
        type: Array,
        required: true,
    },
});
// console.log("competences", props.competences);
const { user } = useUserStore();
const emit = defineEmits(["onUpdate", "onDelete"]);

const editCompetenceModalHandle = (competence, index) => {
    const options = {
        component: CompetenceModal,
        bind: {
            title: trans("Edit competence"),
            value: competence,
        },
        on: {
            saveElement: (element) => {
                emit("onUpdate", element, index);
            },
        },
    };
    $vfm.show(options);
};

const deleteClick = (competence, index) => {
    const title = trans("Delete competence");
    const description = {
        text: "Deleting competece message “:competence“",
        replace: {
            competence: competence.lang_code
                ? languagesPlugin.getName(
                      competence.lang_code,
                      user.language.code
                  )
                : competence.other_competence,
        },
    };

    $vfm.show("confirmation", {
        title,
        description,
        yesClick: () => deleteCompetence(competence, index),
    });
};
const deleteCompetence = (competence, index) => {
    emit("onDelete", competence, index);
};

const $vfm = inject("$vfm");
</script>

<template>
    <vue-final-modal
        classes="flex justify-center items-center"
        content-class="bg-white px-[77px] pt-12 pb-[54px] rounded-sm w-[621px]"
        :keep-overlay="true"
        :click-to-close="false"
        v-slot="{ close }"
    >
        <CrossIcon class="ml-auto cursor-pointer" @click="close" />
        <form class="w-full h-full" @submit.prevent="submit">
            <h1
                class="text-[20px] mb-[42px] text-heading leading-6 font-semibold"
            >
                {{ title }}
            </h1>

            <SingleSelect
                label="Type"
                asterisk="true"
                placeholder="Select type"
                :options="competence_type"
                v-model="form.type"
                optionsClass="w-full right-0"
                class="mb-10"
                :error="v$.type.$errors.length > 0"
            />
            <SingleSelect
                label="Competence"
                v-if="form.type == 'other_competence'"
                asterisk="true"
                placeholder="Select competence"
                :options="other_competence"
                v-model="form.other_competence"
                optionsClass="w-full right-0"
                class="mb-10"
                :error="form.errors.has('other_competence')"
                @onUpdate="
                    () => {
                        if (form.other_competence.length > 0) {
                            return form.errors.clear('other_competence');
                        }
                    }
                "
            />
            <SingleLanguageSelect
                v-if="form.type == 'language'"
                v-model="form.lang_code"
                label="Competence"
                placeholder="Search"
                labelClass="whitespace-nowrap"
                :asterisk="true"
                :searchable="false"
                class="mb-10"
                :error="form.errors.has('lang_code')"
                @onUpdate="
                    () => {
                        if (form.lang_code.length > 0) {
                            return form.errors.clear('lang_code');
                        }
                    }
                "
            />

            <SingleSelect
                v-if="form.type == 'language'"
                v-model="form.level"
                label="Label"
                placeholder="Select level"
                asterisk="true"
                :options="labels"
                optionsClass="w-full right-0"
                class="mb-10"
                :error="form.errors.has('label')"
                @onUpdate="
                    () => {
                        if (form.level.length > 0) {
                            return form.errors.clear('label');
                        }
                    }
                "
            />

            <SingleSelect
               v-if="form.type"
                v-model="form.status"
                label="Competence status"
                placeholder="Select status"
                asterisk="true"
                :options="status"
                optionsClass="w-full right-0"
                class="mb-10"
            />

            <div class="btns flex justify-center gap-[18px] pt-5">
                <ButtonGradient class="w-full h-[48px]" type="submit">
                    {{ $t("Save") }}
                </ButtonGradient>

                <ButtonWhite
                    class="w-full"
                    @click="() => showCancelModal(close)"
                    type="button"
                >
                    {{ $t("Cancel") }}
                </ButtonWhite>
            </div>
        </form>
    </vue-final-modal>
</template>

<script setup>
import { useVuelidate } from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import ButtonGradient from "../../../components/button/Gradient.vue";
import ButtonWhite from "../../../components/button/White.vue";
import SingleSelect from "../../../components/form/SingleSelect.vue";
import SingleLanguageSelect from "../../../components/form/SingleLanguageSelect.vue";
import CrossIcon from "@/components/icons/Cross.vue";
import competence_type from "@/competence_type.json";
import other_competence from "@/other_competence.json";
import labels from "@/labels.json";
import status from "@/status.json";
import { inject, watch } from "@vue/runtime-core";
import { ref, reactive } from "@vue/reactivity";
import { storeToRefs } from "pinia";
import { languageStore } from "@/store/language.js";
import vForm from "vform";
import { propsToAttrMap } from "@vue/shared";
import dayjs from 'dayjs'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    edit: {
        type: Boolean,
        default: false,
    },
    value: {
        type: Object,
        required: false,
        default: () => ({
            created_at: dayjs(new Date()).format('YYYY-MM-DD'),
            type: "",
            lang_code: "",
            other_competence: "",
            level: "",
            status: "confirmed",
        }),
    },
    submit_to_backend: {
        type: Boolean,
        default: false,
    },
});

const competence_list = ref("language");
const label = ref("");
const { formatedLanguage } = storeToRefs(languageStore());
const emit = defineEmits(["saveElement"]);
const $vfm = inject("$vfm");

const showCancelModal = (close) => {
    // console.log("props.edit", props.edit);
    $vfm.show("confirmation", {
        title: props.edit ? "Discard changes?" : "Cancel competency assessment",
        description: props.edit
            ? "If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?"
            : "Cancel competence",
        yesClick: close,
    });
};

const form = reactive(new vForm({ ...props.value }));

watch(
    () => form.type,
    (competence) => {
        if (competence == "language") {
            form.other_competence = "";
        } else if (competence == "other_competence") {
            form.lang_code = "";
            form.level = "";
        }
    }
);

const rules = {
    type: {
        required: helpers.withMessage("", required),
    },
};

const v$ = useVuelidate(rules, form);

// Label required check when competence_type language
const validateLabel = () => {
    let error = false;
    if (form.type == "language" && !form.level) {
        form.errors.set("label", "Field Missing");
        error = true;
    }
    if (form.type == "language" && !form.lang_code) {
        form.errors.set("lang_code", "Field Missing");
        error = true;
    }
    return error;
};

const validateCompetence = () => {
    let error = false;
    if (form.type == "other_competence" && !form.other_competence) {
        form.errors.set("other_competence", "Field Missing");
        error = true;
    }
    return error;
};
const submit = async () => {
    v$.value.$touch();
    const isvalidateLabel = validateLabel();
    const isvalidateCompetence = validateCompetence();
    if (v$.value.$invalid || isvalidateLabel || isvalidateCompetence) {
        $vfm.show("field-missing");
        return;
    }
    // console.log(form.data())

    emit("saveElement", form.data());
    $vfm.hideAll();
};
</script>

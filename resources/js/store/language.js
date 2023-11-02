import { defineStore } from "pinia";
import axios from "axios";
//import { useLanguageCodeToName } from "@/composables/translation.js";

export const languageStore = defineStore("language", {
    state: () => ({
        language: [],
    }),

    actions: {
        async fetchLanguage() {
            try {
                const res = await axios.get(route("language.index"));
                this.language = res.data;
            } catch (error) {
                console.log(error);
            }
        },
    },
    getters: {
        formatedLanguage: (state) => {
            const systemlang = localStorage.getItem("lang");
            return state.language.map(({ id: value, name: label }) => ({
                value,
                label:
                    label == "German" && systemlang == "de" ? "Deutsch" : label,
            }));
        },
        defaultLanguage: (state) => {
            return state.language.find(({ code }) => code == "de");
            // return data;
        },
    },
});


import countries from 'i18n-iso-countries'

// const co = require("i18n-iso-countries/langs/en.json");
// Support french & english languages.

import enJson from "i18n-iso-countries/langs/en.json"
countries.registerLocale(enJson);

import deJson from "i18n-iso-countries/langs/de.json"
countries.registerLocale(deJson);

import languagesPlugin from "@cospired/i18n-iso-languages"
import enLangJson from "@cospired/i18n-iso-languages/langs/en.json"
import deLangJson from "@cospired/i18n-iso-languages/langs/de.json"

languagesPlugin.registerLocale(enLangJson);
languagesPlugin.registerLocale(deLangJson);


import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';
dayjs.extend(customParseFormat);

import { mapStores, mapActions } from 'pinia'
import { useUserStore } from '@/store/user'

export default {
    computed: {
        ...mapStores(useUserStore)
    },
    methods: {
        ...mapActions(useUserStore, ['hasAnyPermissions', 'hasPermission']),
//         getCountryName(countryCode, locale = null){
//             let localeCode = locale ? locale : usePage().props.value.auth_user.language.code;
// //
//             return countries.getName(countryCode.toString().toUpperCase(), localeCode.toString().toLowerCase())
//         },
        // getCountryCode(countryName, locale = null){
        //     let localeCode = locale ? locale : usePage().props.value.auth_user.language.code;
        //     return countries.getAlpha2Code(countryName.toString(), localeCode.toString().toLowerCase())
        // },
        formateDate(date, formate = 'DD.MM.YYYY') {
            return dayjs(date).format(formate).toString()
        },
        formateTime(time, formate = 'HH:mm') {
            // console.log(formate)
            return dayjs(time, 'HH:mm:ss').format(formate).toString()
        },
        translateLanguageName(languageCode){
            const {user} = useUserStore();
            const userLang = user.language.code

            return  languagesPlugin.getName(languageCode, userLang)
        }
    },

}

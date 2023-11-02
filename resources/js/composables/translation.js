import { useUserStore } from '@/store/user';
import countries from 'i18n-iso-countries'
import { storeToRefs } from 'pinia';

import languagesPlugin from "@cospired/i18n-iso-languages"
import enLangJson from "@cospired/i18n-iso-languages/langs/en.json"
import deLangJson from "@cospired/i18n-iso-languages/langs/de.json"

languagesPlugin.registerLocale(enLangJson);
languagesPlugin.registerLocale(deLangJson);

export const useGetCountryName = (countryCode, locale = null) => {
    if (!countryCode) return '';

    const userStore = useUserStore()
    const { user } = storeToRefs(userStore)
    let localCode = locale ? locale : user.value ? user.value.language.code : 'de';

    return countries.getName(countryCode.toString().toUpperCase(), localCode.toString().toLowerCase())
}

export const useGetCountryCode = (countryName, locale = null) => {
    if (!countryName) return '';
    const userStore = useUserStore()
    const { user } = storeToRefs(userStore)
    let localCode = locale ? locale : user.value ? user.value.language.code : 'de';

    return countries.getAlpha2Code(countryName.toString(), localCode.toString().toLowerCase())
}

export const useLanguageCodeToName = (code) => {
    const { user } = useUserStore();
    const userLang = user.language.code

    return languagesPlugin.getName(code, userLang)
}

import './bootstrap';
import { createApp } from 'vue'
import { createPinia } from "pinia";
import App from './App.vue'
import router from './router/index';
import mixin from './mixins/mixin'

import { i18nVue } from 'laravel-vue-i18n'
import VueClickAway from "vue3-click-away";
import VCalendar from 'v-calendar';
import { vfmPlugin } from 'vue-final-modal'

import vDateFormat from './directives/vDateFormat';
import vTrans from './directives/vTrans';

import 'v-calendar/dist/style.css';

import mitt from 'mitt';
const emitter = mitt();

import { useUserStore } from './store/user';

const pinia = createPinia();
const app = createApp(App)
    .mixin(mixin)
    .use(i18nVue, {
        lang: 'de',
        resolve: (lang) => import(`../lang/${lang}.json`)
    })
    .use(pinia)
    .use(VueClickAway)
    .use(vfmPlugin)
    .use(VCalendar, {})
    .directive('date-format', vDateFormat)
    .directive('trans', vTrans)
    .provide("emitter", emitter)

const token = localStorage.getItem("token");
if (token) {
    const userStore = useUserStore()
    await userStore.fetchUser()
}
app.config.globalProperties.emitter = emitter;
app.use(router)
    .mount("#app")


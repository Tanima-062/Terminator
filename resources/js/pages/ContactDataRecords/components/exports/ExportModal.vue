<template>
    <vue-final-modal
      v-model="show"
      name="export-modal"
      classes="flex justify-center items-center"
      content-class="bg-white px-[70px] py-[55px] rounded-sm w-[518px]"
      :click-to-close="false"
      v-slot="{ close, params: { title, tab } }"
    >
      <h2 class="text-2xl text-heading font-semibold font-poppins mb-[22px]">
        {{ $t(title ?? "E-Mail Address") }}
      </h2>



      <TextInput placeholder="Enter email address export modal" :asterisk="false" maxLength="31"
            v-model="form.email" class="w-[378px]"
        />

      <div class="flex items-start justify-center gap-4 mt-[52px]">
        <ButtonGradient @click="ok(close, tab)" class="w-[127px] h-[48px]">
          {{ $t("Ok") }}
        </ButtonGradient>

        <ButtonWhite @click="close" class="w-[127px] h-[48px]">
          {{ $t("Cancel") }}
        </ButtonWhite>
      </div>
    </vue-final-modal>
  </template>

  <script setup>
  import {inject, reactive} from 'vue'
  import { ref } from "@vue/reactivity";
  import ButtonGradient from "@/components/button/Gradient.vue";
  import ButtonWhite from "@/components/button/White.vue";
  import TextInput from "@/components/form/TextInput.vue";


  import { useRouter, useRoute } from "vue-router";
//   import Form from "vform";

    const vueRoute = useRoute()

    const show = ref(false);
    const $vfm = inject("$vfm");

    const form = ref({
        email: null
    });

    const ok = async (close, tab) => {
        // $vfm.hide("export-modal");
        // close();

        const data = {
            ...form.value,
            ...vueRoute.query,
            tab: tab
        }

        await axios.post(route('contact-data-records.exports.email'), data)
            .then(res=>{
                form.value.email = null;
                close();

                // this.$vfm.show('success-notification', {
                //     description: trans('The selected lead(s) were successfully allocated.'),
                //     // duration: 10000
                // })
            })
            .catch(err=> {
                console.log(err);
            })
    }

  </script>

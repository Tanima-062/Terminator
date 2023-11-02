<template>
    <vue-final-modal v-model="show" name="appointment-lead-modal" classes="flex justify-center items-center"
        content-class="bg-white pt-11 px-[45px] pb-[45px] rounded-sm w-[747px]" :click-to-close="false">
        <h2 class="text-2xl text-heading font-semibold font-poppins mb-[25px] text-center" v-if="title" v-trans="title">
        </h2>

        <!-- v-trans="title" -->
        <p class="description" v-if="description" v-trans="description"></p>
        <!-- v-trans="description" -->

        <div class="footer flex items-start justify-center gap-4 mt-16">
            <ButtonGradient @click="setAppointmentLead" class="w-[127px] h-[48px]">
                {{ $t("Ok") }}
            </ButtonGradient>
            <ButtonWhite @click="closeModal" class="w-[127px] h-[48px]">
                {{ $t("No") }}
            </ButtonWhite>
        </div>
    </vue-final-modal>
</template>

<script>
import ButtonGradient from "@/components/button/Gradient.vue";
import ButtonWhite from "@/components/button/White.vue";
import axios from 'axios';

export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            required: true
        },
        contact_data_records: {
            type: Object,
            required: true
        }
    },
    components: {
        ButtonGradient,
        ButtonWhite
    },
    data() {
        return {
            show: false,
        }
    },
    methods: {

        async setAppointmentLead() {
            try {
                const { data } = await axios.post(route('contact-data-records.set-appointment-lead'), {
                    contact_data_records: this.contact_data_records
                })

                this.show = false
                this.$vfm.hide('appointment-lead-modal');

                setTimeout(() => location.reload(), 3000)
            } catch (error) {
                console.log(error)
            }
        },
        closeModal() {
            this.$vfm.hide("appointment-lead-modal");
        }
    }
}
</script>


<style lang="scss" scoped>
.description {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-size: 20px;
    line-height: 141.02%;
    /* or 28px */

    text-align: center;

    color: #5F4C5C;

}
</style>

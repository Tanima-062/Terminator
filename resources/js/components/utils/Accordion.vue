<template>
    <div class="accordion">
        <div class="accordion__header flex justify-between items-center rounded-t-lg" :class="{'rounded-b-lg': !open, 'rounded-b-none': open}">
            <h4 class="accordion__header--title">{{ $t(title) }}</h4>
            <div class="icon" @click="open = !open">
                <up-arrow-icon v-if="open" />
                <down-arrow-icon v-else />
            </div>
        </div>
        <div class="accordion__body" :class="$attrs.bodyClass" v-if="open">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import DownArrowIcon from '@/components/icons/DownArrowIcon.vue'
import UpArrowIcon from '@/components/icons/UpArrowIcon.vue'
    export default {
        components: { UpArrowIcon,DownArrowIcon },
        props: {
            title: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                open: true,
            }
        },
    }
</script>

<style lang="scss" scoped>
    .accordion {
        width: 100%;
        background: #FFFFFF;
        border: 1px solid #E6DEE5;
        box-shadow: 1px 1px 14px rgba(89, 89, 89, 0.1);
        border-radius: 8px 8px 8px 8px;

        &__header {
            // height: 50px;
            background: #F9FAFB;
            // border-radius: 8px 8px 0px 0px;
            padding: 14px 30px;
            padding-right: 15px;
            &--title {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 600;
                font-size: 20px;
                line-height: 24px;
                letter-spacing: 0.02em;
                color: #AB326F;
            }

            .icon {
                width: 20px;
                cursor: pointer;
            }
        }

        &__body {
            border-top: 1px solid #E6DEE5;
            padding-top: 36px;
            padding-bottom: 66px;
            padding-left: 30px;
            padding-right: 85px;
        }
    }
</style>

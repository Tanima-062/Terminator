<template>
    <div class="tr">
        <div class="td" style="width: 15%">
            {{ formateDate(internal_users.created_at) }}
        </div>
        <div class="td" style="width: 20%">
            {{ langSelect(internal_users.correspondence_language_code) }}
        </div>
        <div class="td" style="width: 20%">
            {{
                internal_users.user.campaign_internal.length > 0
                    ? internal_users.user.campaign_internal[0].campaign.name
                    : ""
            }}
        </div>
        <div class="td td-link" style="width: 15%">
            <router-link
                class="text-[#13A3E5]"
                :to="{
                    name: 'internal-user-show',
                    params: { id: internal_users.id },
                }"
            >
                {{ internal_users.user.full_name }}
            </router-link>
        </div>

        <div class="td" style="width: 15%">
            {{ $t(internal_users.roles[0]) }}
        </div>
        <div class="td" style="width: 15%">
            <Status :internal_users="internal_users" />
        </div>
        <div class="td" style="width: 3%">
            <MenuBarVue :internal_users="internal_users" />
        </div>
    </div>
</template>

<script setup>
import MenuBarVue from "./MenuBar.vue";
import Status from "./Status.vue";
import languagesPlugin from "@cospired/i18n-iso-languages";
import { useUserStore } from "@/store/user";
const { user } = useUserStore();

const props = defineProps({
    internal_users: {
        type: Object,
        required: true,
    },
});
const langSelect = (userKoreLang) => {
    return languagesPlugin.getName(userKoreLang, user.language.code);
};
</script>

<style lang="scss" scoped></style>

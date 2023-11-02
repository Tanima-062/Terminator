<template>
    <div class="table__menubar" v-click-away="() => (openDropdownMenu = false)">
        <svg
            class="bar-icon"
            width="18"
            height="12"
            viewBox="0 0 18 12"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            @click="openDropdownMenu = !openDropdownMenu"
        >
            <path
                d="M18 12H0V10H18V12ZM18 7H0V5H18V7ZM18 2H0V0H18V2Z"
                fill="#636363"
            />
        </svg>
        <ul class="table__menubar__dropdown" v-if="openDropdownMenu">
            <li
                v-if="
                    status == 'new' ||
                    status == 'pending' ||
                    status == 'email_verification_pending'
                "
            >
                <a @click.prevent="sendInvitationEmail()">
                    {{ $t("Send Invitation") }}</a
                >
            </li>
            <li>
                <router-link
                    :to="{
                        name: 'internal-user-show',
                        params: { id: internal_users.id },
                    }"
                    >{{ $t("View Details") }}</router-link
                >
            </li>
            <li>
                <router-link
                    :to="{
                        name: 'internal-user-edit',
                        params: { id: internal_users.id },
                    }"
                    >{{ $t("Edit") }}</router-link
                >
            </li>
            <li>
                <a
                    v-if="status == 'inactive'"
                    @click.prevent="changeStatus('active')"
                >
                    {{ $t("Activate") }}</a
                >
            </li>
            <li>
                <a
                    v-if="status == 'active'  || status == 'pending' || status == 'email_verification_pending'"
                    @click.prevent="changeStatus('inactive')"
                    >{{ $t("Deactivate") }}</a
                >
            </li>
        </ul>
    </div>
</template>

<script>
import { trans } from "laravel-vue-i18n";
import axios from "axios";

export default {
    props: {
        internal_users: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            status: this.internal_users.user.status,
            openDropdownMenu: false,
        };
    },
    methods: {
        changeStatus(status) {
            const title =
                status == "active"
                    ? "Activate internal user?"
                    : "Disable internal user?";
            const description =
                status == "active"
                    ? {
                          text: "Are you sure you really want to activate the internal user :name?",
                          replace: { name: this.internal_users.user.name },
                      }
                    : {
                          text: "Are you sure you really want to deactivate the internal user :name?",
                          replace: { name: this.internal_users.user.name },
                      };

            this.$vfm.show("confirmation", {
                title,
                description,
                yesClick: () => this.updateStatus(status),
            });
        },
        async updateStatus(status) {
            const res = await axios.put(
                route("internal-users.update-status", {
                    internal_users: this.internal_users.id,
                }),
                { status: status, id: this.internal_users.user.id }
            );
            const description =
                status == "active"
                    ? "The internal user :name has been successfully activated."
                    : "The internal user :name was successfully deactivated";

            this.$vfm.show("success-notification", {
                description: {
                    text: description,
                    replace: { name: this.internal_users.user.name },
                },
            });
            this.status = status;
            this.emitter.emit(
                `update-status-${this.internal_users.id}`,
                status
            );
        },

        sendInvitationEmail() {
            axios.post(
                route("internal-users.send-invitation-email", {
                    user: this.internal_users.user.id,
                })
            );
            this.$vfm.show("success-notification", {
                description: trans(
                    "The Email invitation was successfully sent."
                ),
            });

            this.openDropdownMenu = false;
        },
    },
};
</script>

<style lang="scss" scoped></style>

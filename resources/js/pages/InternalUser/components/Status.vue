<template>
    <div class="status flex items-center gap-[8.3px]">
        <svg
            width="12"
            height="12"
            viewBox="0 0 10 10"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <circle cx="5" cy="5" r="5" :fill="fillColor" />
        </svg>
        <span class="status-tooltip">{{ $t(statusText) }}</span>
    </div>
</template>

<script>
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
        };
    },
    computed: {
        fillColor() {
            if (this.status == "inactive") {
                return "#F93232";
            } else if (this.status == "active") {
                return "#439F6E";
            } else if (this.status == "new") {
                return "#22794B";
            } else if (this.status == "pending") {
                return "#FFB82E";
            } else if (this.status == "email_verification_pending") {
                return "#FFB82E";
            }

            return "";
        },
        statusText() {
            if (this.status == "inactive") {
                return "inactive";
            } else if (this.status == "active") {
                return "active";
            } else if (this.status == "new") {
                return "new";
            } else if (this.status == "pending") {
                return "registration_pending";
            } else if (this.status == "email_verification_pending") {
                return "email_verification_pending";
            }

            return "";
        },
    },
    mounted() {
        this.emitter.on(`update-status-${this.internal_users.id}`, (status) => {
            this.status = status;
        });
    },
};
</script>

<style lang="scss" scoped>
.status {
    position: relative;
    font-family: "Inter";
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 19px;

    color: #636363;

    .status-tooltip {
        font-family: "Inter";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 19px;

        color: #636363;
    }

    // .status-tooltip {
    //     position: absolute;
    //     top: 0;
    //     left: 25px;
    //     display: none;
    //     padding: 5px 10px;
    //     border-radius: 4px;
    //     background-color: black;
    //     color:white;
    //     z-index: 999;
    //     font-size: 14px;
    //     line-height: 16px;
    //     // font-weight: 700;
    //     text-align: center;
    //     min-width: 70px;

    //     animation: .8s ease-in-out;

    //     &::before {

    //       content: "";
    //       width: 0;
    //       height: 0;
    //       border-style: solid;
    //       border-width: 5px 7.5px 5px 0;
    //       border-color: transparent black transparent transparent;
    //       display: inline-block;
    //       position: absolute;
    //       left: -7px;
    //       top: 50%;
    //       transform: translateY(-50%);
    //     }
    // }
}

svg:hover + .status-tooltip {
    display: block;
}
</style>

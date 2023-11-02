<template>
    <div class="sort flex items-center">
        <template v-if="typeof label == 'object'">
            {{ $t(label.text, label.replace) }}
        </template>
        <template v-else>
            {{ $t(label) }}
        </template>
        <svg
            width="10"
            height="13"
            viewBox="0 0 10 13"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            @click="sort"
            v-if="enable"
        >
            <path
                d="M5.21941 0L9.12724 4.875H1.31157L5.21941 0Z"
                fill="#ADB5BD"
            />
            <path
                d="M5.21941 13L9.12724 8.125H1.31157L5.21941 13Z"
                fill="#ADB5BD"
            />
        </svg>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: [String, Object],
            required: true,
        },
        columnName: {
            type: String,
            required: true,
        },
        enable: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            direction: null,
        };
    },
    created() {
        const order_by = this.$route.query.order_by;
        const direction = this.$route.query.direction;
        if (this.columnName == order_by) this.direction = direction;
    },
    watch: {
        direction(newValue, oldValue) {
            const queries = this.$route.query;
            const routeObj = {
                name: route.name,
                params: { ...this.$route.params },
                query: {
                    ...queries,
                    order_by: this.columnName,
                    direction: this.direction,
                },
            };

            this.$router.push(routeObj);
        },
    },
    methods: {
        sort() {
            if (!this.enable) return;
            const direction = this.direction ? this.direction : "DESC";
            this.direction = direction.toUpperCase() == "ASC" ? "DESC" : "ASC";
        },
    },
};
</script>

<style lang="scss" scoped>
.sort {
    font-family: "Roboto";
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 22px;
    letter-spacing: 0.02em;
    color: #adb5bd;
    svg {
        margin-left: 5px;
        cursor: pointer;
    }
}
</style>

import { defineStore } from "pinia";
import axios from "axios";

export const brokersStore = defineStore("brokers", {
    state: () => ({
        brokers: [],
    }),

    actions: {
        async fetchBroker() {
            try {
                const res = await axios.get(route("brokers.fetch"));
                this.brokers = res.data;
            } catch (error) {
                console.log(error);
            }
        },
    },
    getters: {
        formatedBrokers: (state) => {
            return state.brokers.map(({ id: value, name: label }) => ({
                value,
                label,
            }));
        },
    },
});

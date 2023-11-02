import { defineStore } from "pinia";
import axios from "axios";

export const companyRolesStore = defineStore("roles", {
    state: () => ({
        roles: [],
    }),

    actions: {
        async fetchCompanyRole() {
            try {
                const res = await axios.get(route("company-role.index"));
                this.roles = res.data;
            } catch (error) {
                console.log(error);
            }
        },
    },
    getters: {
        formatedRoles: (state) => {
            return state.roles.map(({ id: value, name: label }) => ({
                value,
                label,
            }));
        },
    },
});

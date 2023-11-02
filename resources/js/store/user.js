import { defineStore } from "pinia";
import axios from "axios";

export const useUserStore = defineStore("user", {
    state: () => ({
        user: null,
        authenticated: false,
    }),

    actions: {
        async fetchUserCustomerCompany() {
            try {
                const res = await axios.get(
                    route("customer-companies.show", {
                        customer_company: this.user.customer_company_id,
                    })
                );
                this.user.customer_company = res.data;
            } catch (error) {
                console.log(error);
            }
        },
        async fetchUser() {
            // try {
            //     const res = await axios.get(route("user"));

            //     this.user = res.data.data;

            //     if (
            //         this.user.status == "inactive" ||
            //         this.user.status == "email_verification_pending"
            //     ) {
            //         this.authenticated = false;
            //         localStorage.setItem("token", "");
            //     } else if (
            //         this.user.status == "active" &&
            //         this.user.type == "company_admin"
            //     ) {
            //         this.fetchUserCustomerCompany();
            //         this.authenticated = true;
            //     } else if (this.user.type == "system_admin") {
            //         this.authenticated = true;
            //     }
            // } catch (error) {
            //     localStorage.removeItem("token");
            //     this.authenticated = false;
            // }


            try {
                const res = await axios.get(route('user'));

                this.user = res.data.data;

                if (this.user.type == 'company_admin') this.fetchUserCustomerCompany();
                this.authenticated = true;

            } catch (error) {
                localStorage.removeItem('token')
                this.authenticated = false;
            }
        },
        setData(data) {
            this.user = data.user;
            localStorage.setItem("token", data.token);
            this.authenticated = true;
        },
        removeData() {
            // this.user = null;
            // localStorage.removeItem('token')
            // this.authenticated = false;
            // console.log("called");
        },
        // async signIn(data) {
        //   const login_url = `${import.meta.env.VITE_API_URL}/login`
        //   try {
        //     const res = await axios.post(login_url, data)
        //     this.user = res.data.user
        //     console.log(res)
        //     localStorage.setItem('token', res.data.token)
        //     this.authenticated = true;
        //   } catch (error) {
        //     throw new Error(error);
        //   }
        // },
        hasPermission(permision) {
            return this.user.permissions.includes(permision);
        },
        hasAnyPermissions(permisions) {
            let find = false;
            for (let i = 0; i < permisions.length; i++) {
                if (this.user.permissions.includes(permisions[i])) {
                    find = true;
                    break;
                }
            }

            return find;
        },

        hasRole(role) {
            return typeof role == 'object' ? role.includes(this.user.role) : role == this.user.role
        }
    },
});

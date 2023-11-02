import { defineStore } from 'pinia'
import { storeToRefs } from 'pinia'

import { useUserStore } from '@/store/user'

export const usePermissionStore = defineStore('permission', {
    getters: {
        permissions(state) {
          const userStore = useUserStore()
          const { user } = storeToRefs(userStore)

          return user.value.permissions
        },
    },

    actions: {
        hasPermission(permision) {
            return this.permissions.includes(permision)
        },
        hasAnyPermissions(permisions) {
            let find = false;
            for (let i = 0; i < permisions.length; i++){
                if (this.permissions.includes(permisions[i])) {
                    find = true;
                    break;
                }
            }

            return find;
        },
    },
})

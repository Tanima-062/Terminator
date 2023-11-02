import { defineStore } from 'pinia';

export const notificationShowStore = defineStore({
    id: 'notification',
    state: () => ({
        notification: null
    }),
    actions: {
        success(description, title = "Notification") {
            this.notification = { description: description, title: title };
        },
        clear() {
            this.notification = null;
        }
    }
});

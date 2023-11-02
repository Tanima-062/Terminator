import { useUserStore } from '../store/user'

const beforeEach = (to, from, next) => {
    document.title = to.meta.title ?? 'Termin-ator'
    const { authenticated } = useUserStore();

    if (to.meta.middleware == "guest") {
        if (authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (authenticated) {
            next()
        } else {
            next({ name: "login" })
        }

        // next()
    }
}

export default beforeEach;

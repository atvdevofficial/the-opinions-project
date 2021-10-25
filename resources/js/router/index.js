import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes';

Vue.use(VueRouter)

const router = new VueRouter({
    mode: "history",
    routes: routes,
})

// User role route permissions
const modules = {
    login: true,
    feed: true,
    chats: true,
    chat: true
}

// Roles modules
const roleModules = {
    critique: {
        ...modules,
        default: 'feed',
        login: false,
    },
    guest: {
        ...modules,
        default: 'login',
        feed: false,
        chats: false,
        chat: false
    }
}

// router beforeEach
router.beforeEach((to, from, next) => {
    let userRole = sessionStorage.getItem('userRole') ?? 'guest';

    if (!roleModules[userRole.toLocaleLowerCase()][to.name]) {
        next({ name: roleModules[userRole.toLocaleLowerCase()]['default'] })
    } else next()
})

export default router

import Vue from 'vue'
import Router from 'vue-router'
import UserDetail from '@/views/user/Detail'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'UserDetail',
            component: UserDetail 
        }
    ]
})

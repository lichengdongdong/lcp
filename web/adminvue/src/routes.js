import Login from '@/views/Login.vue'
import NotFound from '@/views/404.vue'
import Home from '@/views/Home.vue'
import Main from '@/views/Main.vue'

//职位
import GetCode from '@/views/robot/GetCode.vue' //简历列表C

let routes = [
    {
        path: '/login',
        component: Login,
        name: '',
        hidden: true
    },
    {
        path: '/404',
        component: NotFound,
        name: '',
        hidden: true
    },
    {
        path: '/',
        component: Home,
        name: '统计',
        iconCls: 'el-icon-menu',//图标样式class
        children: [
            /*{ path: '/main', component: Main, name: '主页', hidden: true },*/
            { path: '/getcode', component: GetCode, name: '编码' }
        ]
    },
    {
        path: '*',
        hidden: true,
        redirect: { path: '/404' }
    }
];

export default routes;
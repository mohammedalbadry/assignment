import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "../store/index"

import App from '../layouts/App.vue'
import Base from '../layouts/Base.vue'

import MyLogin from '../views/dashboard/main_pages/MyLogin.vue'
import Myforgot_password from '../views/dashboard/main_pages/Myforgot_password.vue'
import Myreset_password from '../views/dashboard/main_pages/Myreset_password.vue'

import Myhome from '../views/dashboard/main_pages/Myhome.vue'
import Mysetting from '../views/dashboard/main_pages/Mysettings.vue'
import Myprofile from '../views/dashboard/main_pages/Myprofile.vue'

import users from '../views/dashboard/users/index.vue'
import users_create from '../views/dashboard/users/create.vue'
import users_edit from '../views/dashboard/users/edit.vue'
import users_show from '../views/dashboard/users/show.vue'

import roles from '../views/dashboard/roles/index.vue'
import role_create from '../views/dashboard/roles/create.vue'
import role_edit from '../views/dashboard/roles/edit.vue'
import role_show from '../views/dashboard/roles/show.vue'

import admins from '../views/dashboard/admins/index.vue'
import admin_create from '../views/dashboard/admins/create.vue'
import admin_edit from '../views/dashboard/admins/edit.vue'
import admin_show from '../views/dashboard/admins/show.vue'

import pharmacies from '../views/dashboard/pharmacies/index.vue'
import pharmacy_create from '../views/dashboard/pharmacies/create.vue'
import pharmacy_edit from '../views/dashboard/pharmacies/edit.vue'
import pharmacy_show from '../views/dashboard/pharmacies/show.vue'

import timetables from '../views/dashboard/timetables/index.vue'
import timetable_create from '../views/dashboard/timetables/create.vue'
import timetable_edit from '../views/dashboard/timetables/edit.vue'
import timetable_show from '../views/dashboard/timetables/show.vue'

import NotFound from '../views/dashboard/main_pages/404.vue'
import Forbidden from '../views/dashboard/main_pages/403.vue'

import user_login from '../views/enduser/Login.vue'
import user_register from '../views/enduser/Register.vue'
import user_forgot_password from '../views/enduser/Forgot_password.vue'
import user_reset_password from '../views/enduser/Reset_password.vue'

import user_home from '../views/enduser/Home.vue'
import user_settings from '../views/enduser/Settings.vue'


Vue.use(VueRouter)


const router = new VueRouter({
    mode: 'history',
    routes:[
        {
            path: '/',
            name: 'base',
            component: Base,
            redirect: '/home',
            children: [
                {
                    path: '/home',
                    name: 'enduser',
                    component: user_home,
                    beforeEnter: (to, from, next) => {
                        if(store.state.auther == null || store.state.token == null){
                          next('/login')
                        }else{
                          next()
                        }
                    }
                },
                { 
                    path: '/settings',
                    name: 'settings',
                    component: user_settings,
                    beforeEnter: (to, from, next) => {
                        if(store.state.auther == null || store.state.token == null){
                          next('/login')
                        }else{
                          next()
                        }
                    }
                },
                { path: '/login', name: 'login',  component: user_login },
                { path: '/register', name: 'register', component: user_register },
                { path: '/forgot_password',name: 'forgot_password', component: user_forgot_password },
                { path: '/reset_password/:token', name: 'reset_password', component: user_reset_password }
            ],
        },
        { path: '/admin/login', name: 'admin_login', component: MyLogin },
        { path: '/admin/forgot_password', name: 'admin_forgot_password', component: Myforgot_password },
        { path: '/admin/reset_password/:token', name: 'admin_reset_password', component: Myreset_password },
        {
            path: '/admin',
            name: 'app',
            component: App,
            redirect: '/admin/home',
            beforeEnter: (to, from, next) => {
                if(store.state.adminAuther == null || store.state.adminToken == null){
                  next('/admin/login')
                }else{
                  next()
                }
            },
            children: [
                { path: 'home', name: 'admin_home', component: Myhome },
                { path: 'settings', name: 'admin_settings', component: Mysetting },
                { path: 'profile', name: 'admin_profile', component: Myprofile },

                { path: 'users', name: 'users', component: users },
                { path: 'users/create', name: 'users_create', component: users_create },
                { path: 'users/edit/:id', name: 'users_eidt', component: users_edit },
                { path: 'users/show/:id', name: 'users_show',component: users_show },

                { path: 'roles', name: 'roles', component: roles },
                { path: 'roles/create', name: 'roles_create', component: role_create },
                { path: 'roles/edit/:id', name: 'roles_eidt', component: role_edit },
                { path: 'roles/show/:id',  name: 'roles_show', component: role_show },

                { path: 'admins', name: 'admins', component: admins },
                { path: 'admins/create', name: 'admins_create', component: admin_create },
                { path: 'admins/edit/:id', name: 'admins_eidt', component: admin_edit },
                { path: 'admins/show/:id', name: 'admins_show',component: admin_show },

                { path: 'timetables', name: 'timetables_', component: timetables },
                { path: 'timetables/create', name: 'timetables_create', component: timetable_create },
                { path: 'timetables/create/user/:user', name: 'timetables_create_user', component: timetable_create },
                { path: 'timetables/create/pharmacy/:pharmacy', name: 'timetables_create_pharmacy', component: timetable_create },
                { path: 'timetables/edit/:id', name: 'timetables_eidt', component: timetable_edit },
                { path: 'timetables/show/:id', name: 'timetables_show', component: timetable_show },

                { path: 'pharmacies', name: 'pharmacies', component: pharmacies },
                { path: 'pharmacies/create', name: 'pharmacies_create', component: pharmacy_create },
                { path: 'pharmacies/edit/:id', name: 'pharmacies_eidt', component: pharmacy_edit },
                { path: 'pharmacies/show/:id', name: 'pharmacies_show', component: pharmacy_show },

            ],
        },
        { path: "/403",  name: "403", component: Forbidden },
        { path: "*",  name: "notfound", component: NotFound }
    ]
})

router.beforeEach( function(to, from, next){
    if (to.name !== undefined ){
        document.title = to.name
    }
    next();
})
export default router
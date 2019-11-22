/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)


const routes = [
    { path: '/dashboard', component: require('./components/DashboardComponent.vue').default},
    { path: '/users', component: require('./components/UsersComponent.vue').default},
    { path: '/roles', component: require('./components/management/RolesComponent.vue').default},
]

const router = new VueRouter({
    routes // short for `routes: routes`
})

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('user-modal-component', require('./components/usersModals/UserModalComponent').default);


const app = new Vue({
    el: '#app',
    router
});

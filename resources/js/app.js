/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
import { Form, HasError, AlertError } from 'vform'
import swal from 'sweetalert2'
import moment from 'moment';

window.swal = swal;

Vue.use(VueRouter)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

window.toast = toast;
window.vm =  new Vue();
window.Form = Form;


Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate',function(created){
    return moment(created).format('MMMM Do YYYY');
});

const routes = [

    { path: '/dashboard', component: require('./components/DashboardComponent').default},
    { path: '/users', component: require('./components/UsersComponent.vue').default},
    { path: '/roles', component: require('./components/management/RolesComponent').default},
]
const router = new VueRouter({

    routes // short for `routes: routes`
})

const app = new Vue({
    el: '#app',
    router,
});

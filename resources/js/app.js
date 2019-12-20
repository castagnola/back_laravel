/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * Imports
 */
import { Form, HasError, AlertError } from 'vform';
import Auth from './components/packages/auth/Auth';
import VueRouter from 'vue-router';
import Gate from "./Gate/gate";
import swal from 'sweetalert2';
import routes from './routes';
import moment from 'moment';
import Vue from 'vue';

/**
 * Globals uses
 */

Vue.component(AlertError.name, AlertError);
Vue.component(HasError.name, HasError);
Vue.use(VueRouter);
Vue.use(Auth);
// Vue.http.headers.common['X-CSRF-TOKEN'] = Laravel.csrfToken;
// Vue.http.headers.common['Authorization'] = 'Bearer' +Vue.auth.getToken();
Vue.prototype.$gate = new Gate(window.user);

window.swal = swal;

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


/**
 * Routes CallBack
 * @type {VueRouter}
 */
const router = new VueRouter({

    routes // short for `routes: routes`
});

/**
 * Guards from rotes where the user is login at the app
 */
router.beforeEach(
    (to,from,next) => {
        if (to.matched.some(record => record.meta.forVisitors)) {
            if (Vue.auth.isAuthenticated()) {
                next({
                    path:'/dashboard'
                })
            } else next()
        }
        else if (to.matched.some(record => record.meta.forAuth)) {
            if (!Vue.auth.isAuthenticated()) {
                next({
                    path: '/login'
                })
            } else next()
        }
        else  next()
    }
);

Vue.component('nav-bar-component', require('./components/navbar/NavbarComponent').default);
Vue.component('login-component', require('./components/OAuth/LoginComponent').default);


/**
 * Render app
 * @type {Vue | CombinedVueInstance<Vue, object, object, object, Record<never, any>>}
 */
const app = new Vue({
    el: '#app',
    router,
});

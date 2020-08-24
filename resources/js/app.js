require('./bootstrap');


window.Vue = require('vue');

import Vue from 'vue';
Vue.config.productionTip = false

import { FontAwesomeIcon } from '@fortawesome/fontawesome-free';


import router from "./router";
import store from "./store";
import filter from "./filter";
import i18n from "./lang/i18n";

import Permissions from './mixins/Auth';
Vue.mixin(Permissions);

import moment from 'moment';
window.moment = moment;

import VueHtmlToPaper from 'vue-html-to-paper';
const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        "/css/app.css",
    ]
}
Vue.use(VueHtmlToPaper, options);

import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    // timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }
});
window.toast = toast;

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
})

import { objectToFormData } from 'object-to-formdata';
window.objectToFormData = objectToFormData;

import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


Vue.component('pagination', require('laravel-vue-pagination'));
// Vue.component('nav-bar', require('./components/NavBar').default);
Vue.component('app-dashboard', require('./components/dashboard/Index.vue').default);


router.beforeEach( (to, from, next) => {
    if( to.matched.some(record => record.meta.requiresAuth)) {
        if( !store.getters.loggedIn ){
            next({name: 'login'})
        }else{
            next();
        }
    }else{ // public routes
        if(to.name=='login' && store.getters.loggedIn){
            next({name: 'dashboard'})
        }

        next();
    }
})

const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    created() {
        if(store.getters.loggedIn){
            this.$store.dispatch("retrieveUser")
            this.$store.dispatch("retrieveMenus")
        }
    }
});

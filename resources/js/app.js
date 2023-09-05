/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';
import VueSimpleAlert from "vue-simple-alert";
import App from './components/App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import axios from 'axios';


Vue.component('App', require('./components/App.vue').default);

Vue.use(VueSimpleAlert, { reverseButtons: true });

axios.defaults.baseURL = window.location.origin;
if(localStorage.getItem('token'))
{
    axios.defaults.headers.common['Authorization'] = `Bearer `+localStorage.getItem('token')
    axios.defaults.headers.common['Accept'] = `application/json`
}

router.beforeEach((to, from, next) => {
    if(to.path == '/login' || to.path == '/register' || to.path == '/forgot')
    {
        if(localStorage.getItem('token'))
        {
            next('/');
        }
        else
        {
            next();
        }
    }
    next();
});

Vue.filter('stripHTML', function (value) {
    const div = document.createElement('div')
    div.innerHTML = value
    const text = div.textContent || div.innerText || ''
    return text
});

Vue.filter('str_limit', function (value, size) {
    if (!value) return '';
    value = value.toString();

    if (value.length <= size) {
    return value;
    }
    return value.substr(0, size) + '...';
});

const app = new Vue({
    router,
    render: h => h(App),
}).$mount('#app');

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import _ from "lodash";

window.Vue = require('vue').default;

Vue.component('app', require('./components/App.vue').default);

import 'boxicons'

import Vue from 'vue'
import router from './router'
import vuetify from './plugins/vuetify'

new Vue({
    vuetify,
    router
}).$mount('#app')



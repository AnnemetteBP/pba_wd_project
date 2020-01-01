/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vuetify from 'vuetify';
Vue.use(Vuetify);

import DashboardApp from "./DashboardApp.vue";

const opts = {};

const app = new Vue({
    render: h => h(DashboardApp),
    el: "#app",
    vuetify: new Vuetify(opts),
});
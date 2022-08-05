import Vue from 'vue';
import VueRouter from 'vue-router';
import VModal from 'vue-js-modal'
import './bootstrap';
import App from './App.vue';
import routes from "./routes";

Vue.config.productionTip = false;

Vue.use(VueRouter);
Vue.use(VModal);

const router = new VueRouter({ mode: 'history', routes: routes });
new Vue(Vue.util.extend({ router }, App)).$mount('#app');
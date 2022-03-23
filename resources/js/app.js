require('./bootstrap');

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import moment from 'moment'

window.Vue = require('vue').default

Vue.component('dash-component', require('./components/dash.vue').default)
Vue.component('result-component', require('./components/result.vue').default)
Vue.component('card-component', require('./components/cards.vue').default)
Vue.component('stats-component', require('./components/stats.vue').default)
Vue.component('metric-card', require('./components/metric-card.vue').default)
Vue.component('view-dropdown-component', require('./components/view-dropdown.vue').default)
Vue.component('pagination', require('laravel-vue-pagination'))

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(value).fromNow()
    }
})

const app = new Vue({
    el: '#app',
});
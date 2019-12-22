/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import Vuelidate from 'vuelidate'
import vueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css';

window.Vue = require('vue');

// Vue.use(Vuelidate);
Vue.use(vueMultiselect);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('spinner', require('./components/Spinner.vue').default);
Vue.component('episode-list-component', require('./components/EpisodeListComponent.vue').default);
Vue.component('episode-form-component', require('./components/EpisodeFormComponent.vue').default);
Vue.component('program-form-component', require('./components/ProgramFormComponent.vue').default);
Vue.component('program-list-component', require('./components/ProgramListComponent.vue').default);
Vue.component('setting-form-component', require('./components/SettingsFormComponent.vue').default);
Vue.component('multiselect', vueMultiselect);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

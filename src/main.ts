import Vue from 'vue';
import './plugins/vuetify';
import App from './App.vue';
import {router} from './router';
import VeeValidate from 'vee-validate';
import VueSweetalert2 from 'vue-sweetalert2';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueSweetalert2);

Vue.use(VeeValidate);

Vue.use(VueAxios, axios);

Vue.config.productionTip = false;

new Vue({
  router,
  render: (h) => h(App),
}).$mount('#app');

import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import es from 'vuetify/src/locale/es'

Vue.use(Vuetify, {
   theme: {
   primary: '#388e43',
   secondary: '#1B5E20',
   accent: '#00838F',
   error: '#f44336',
   warning: '#ffeb3b',
   info: '#2196f3',
   success: '#4caf50',
   },
   iconfont: 'fa',
   lang: {
      locales: { 'es': es},
      current: 'es'
   }
});

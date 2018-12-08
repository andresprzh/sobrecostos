import Vue from 'vue';
import Router from 'vue-router';
import Home from './components/Home.vue';
import Upfile from './components/Upfile.vue';
import Upcopi from './components/Upcopi.vue';
import Transferencias from './components/Transferencias.vue';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/upfile',
      name: 'Upfile',
      component: Upfile,
    },
    {
      path: '/upcopi',
      name: 'Upcopi',
      component: Upcopi,
    },
    {
      path: '/transferencias',
      name: 'Transferencias',
      component: Transferencias,
    },
  ],
});

import Vue from 'vue';
import Router from 'vue-router';
import Home from './components/Home.vue';
import Upfile from './components/Upfile.vue';
import Upcopi from './components/Upcopi.vue';
import Solicitudes from './components/Solicitudes.vue';
import Remisiones from './components/Remisiones.vue';
import Tabla from './components/Tabla.vue';
import Transferencias from './components/Transferencias.vue';
import Usuarios from './components/Usuarios.vue';


Vue.use(Router);

export const router = new Router({
  // export default algo = new Router({
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import(/* webpackChunkName: "about" */ './components/Login.vue'),
    },
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
      path: '/transferencias',
      name: 'Transferencias',
      component: Transferencias,
    },
    {
      path: '/upcopi',
      name: 'Upcopi',
      component: Upcopi,
    },
    {
      path: '/remisiones',
      name: 'Remisiones',
      component: Remisiones,
      children: [
        {
          path: 'tabla/:id',
          name: 'Tabla',
          component: Tabla,
        },
        {
          path: 'solicitudes/:id',
          name: 'Solicitudes',
          component: Solicitudes,
        },
      ],
    },
    {
      path: '/usuarios',
      name: 'Usuarios',
      component: Usuarios,
    },
    { path: '*', redirect: '/' },
  ],
});

router.beforeEach((to, from, next) => {
  const publicPages = ['/login'];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem('session');

  if (authRequired && !loggedIn) {
    return next('/login');
  }
  next();
});

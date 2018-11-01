import Vue from 'vue';
import Router from 'vue-router';
import HOME from '../views/Home';
import CABINET from '../views/Cabinet';

export const ROUTE_HOME = 'home';
export const ROUTE_CABINET = 'cabinet';

Vue.use(Router);

export default new Router({
  mode:     'history',
  fallback: false,
  routes:   [ {
    path:      '/',
    component: HOME,
    name:      ROUTE_HOME,
  }, {
    path:      '/cabinet',
    component: CABINET,
    name:      ROUTE_CABINET,
  } ],
});

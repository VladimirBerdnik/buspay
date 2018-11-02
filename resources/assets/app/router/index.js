import Vue from 'vue';
import Router from 'vue-router';
import Home from '../views/Home';
import Cabinet from '../views/Cabinet';
import CardTypes from '../views/tables/CardTypes';

export const ROUTE_HOME = 'home';
export const ROUTE_CABINET = 'cabinet';
export const ROUTE_CARD_TYPES = 'cardTypes';

Vue.use(Router);

export default new Router({
  mode:     'history',
  fallback: false,
  routes:   [ {
    path:      '/',
    component: Home,
    name:      ROUTE_HOME,
  }, {
    path:      '/cabinet',
    component: Cabinet,
    name:      ROUTE_CABINET,
    children:  [
      { path: 'cardTypes', component: CardTypes, name: ROUTE_CARD_TYPES },
    ],
  } ],
});

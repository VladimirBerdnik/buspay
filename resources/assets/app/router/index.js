import Vue from 'vue';
import Router from 'vue-router';
import HOME from '../components/Home';

Vue.use(Router);

export default new Router({
  mode:     'history',
  fallback: false,
  routes:   [{
    path:      '/',
    component: HOME,
    name:      'home',
  }],
});

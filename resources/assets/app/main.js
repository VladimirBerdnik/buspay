import Vue from 'vue';
import App from './App';
import i18n from './lang/i18n';
import router from './router';
import store from './store';

// eslint-disable-next-line no-new
new Vue({
  i18n,
  el:     '#app',
  render: h => h(App),
  router,
  store,
});

import 'material-design-icons-iconfont/dist/material-design-icons.css';
import 'vuetify/dist/vuetify.min.css';
import Vue from 'vue';
import Vuetify from 'vuetify';
import VeeValidate from 'vee-validate';
import ru from 'vee-validate/dist/locale/ru';
import App from './App';
import i18n from './lang/i18n';
import router from './router';
import store from './store';
import './utils/filters';

Vue.use(VeeValidate, {
  locale:     'ru',
  dictionary: {
    ru,
  },
});
Vue.use(Vuetify);

// eslint-disable-next-line no-new
new Vue({
  i18n,
  el:     '#app',
  render: h => h(App),
  router,
  store,
});

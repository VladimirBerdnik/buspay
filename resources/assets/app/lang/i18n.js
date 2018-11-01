import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from './ru';

Vue.use(VueI18n);

export default new VueI18n({
  locale:   'ru',
  messages: { ru: messages },
});

import Vue from 'vue';
import Vuex from 'vuex';
import persistedState from 'vuex-persistedstate';
import mutations from './mutations';
import getters from './getters';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    auth: {
      user:          null,
      token:         null,
      decodedToken:  null,
      authenticated: false,
    },
  },
  mutations,
  getters,
  plugins: [
    persistedState({
      paths: ['auth'],
    }),
  ],
});

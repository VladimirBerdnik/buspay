import Vue from 'vue';
import Vuex from 'vuex';
import persistedState from 'vuex-persistedstate';
import mutations from './mutations';
import getters from './getters';
import getInitialState from './initialState';

Vue.use(Vuex);

const state = getInitialState();

export default new Vuex.Store({
  state,
  mutations,
  getters,
  plugins: [
    persistedState({
      paths: ['auth'],
    }),
  ],
});

import Vue from 'vue';
import Vuex from 'vuex';
import persistedState from 'vuex-persistedstate';
import mutations from './mutations';
import getters from './getters';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    // Auth related data
    auth: {
      user:          null,
      token:         null,
      decodedToken:  null,
      authenticated: false,
    },
    // Modal windows data
    loginModal: {
      visible: false,
      params:  {},
    },
    confirmModal: {
      visible: false,
      params:  {
        message: '',
        title:   '',
      },
    },
    errorNotificationModal: {
      visible: false,
      params:  {},
    },
    // Alerts
    alerts:          [],
    // Data lists
    cardTypes:       [],
    tariffPeriods:   [],
    tariffs:         [],
    companies:       [],
    users:           [],
    roles:           [],
    routes:          [],
    buses:           [],
    drivers:         [],
    cards:           [],
    cardsPagination: {
      total:        null,
      count:        null,
      per_page:     null,
      current_page: null,
      total_pages:  null,
    },
    driversCards: [],
    validators:   [],
  },
  mutations,
  getters,
  plugins: [
    persistedState({
      paths: ['auth'],
    }),
  ],
});

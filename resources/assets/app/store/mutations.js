import jwtDecode from 'jwt-decode';

export const LOGIN_MUTATION = 'login';
export const LOGOUT_MUTATION = 'logout';
export const OPEN_CONFIRMATION_MODAL_MUTATION = 'openConfirmationModal';
export const CLOSE_CONFIRMATION_MODAL_MUTATION = 'closeConfirmationModal';
export const OPEN_ERROR_NOTIFICATION_MODAL_MUTATION = 'openErrorNotificationModal';
export const CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION = 'closeErrorNotificationModal';
export const OPEN_LOGIN_MODAL_MUTATION = 'openLoginModal';
export const CLOSE_LOGIN_MODAL_MUTATION = 'closeLoginModal';
export const ADD_ALERT_MUTATION = 'addAlert';
export const FORGOT_ALERT_MUTATION = 'forgotAlert';
export const PROFILE_MUTATION = 'profile';
export const CARD_TYPES_MUTATION = 'cardTypes';
export const TARIFF_PERIODS_MUTATION = 'tariffPeriods';
export const TARIFFS_MUTATION = 'tariffs';
export const COMPANIES_MUTATION = 'companies';
export const USERS_MUTATION = 'users';
export const ROLES_MUTATION = 'roles';
export const ROUTES_MUTATION = 'routes';
export const BUSES_MUTATION = 'buses';
export const DRIVERS_MUTATION = 'drivers';
export const CARDS_MUTATION = 'cards';
export const DRIVERS_CARDS_MUTATION = 'driversCards';

const mutations = {
  /************************************
   * AUTHENTICATION RELATED MUTATIONS *
   ***********************************/
  /**
   * Saves token for Authorization header.
   *
   * @param {Object} state - Vuex state.
   * @param {string} token - Token to use for authorization.
   *
   * @return void
   */
  [LOGIN_MUTATION](state, token) {
    state.auth.token = token;
    state.auth.decodedToken = jwtDecode(token);
    state.auth.authenticated = true;
  },

  /**
   * Clears auth information.
   *
   * @param {Object} state - Vuex state.
   *
   * @return void
   */
  [LOGOUT_MUTATION](state) {
    state.auth.user = null;
    state.auth.token = null;
    state.auth.decodedToken = null;
    state.auth.authenticated = false;
  },

  /*****************************
   * PROFILE RELATED MUTATIONS *
   ****************************/
  /**
   * Saves token for Authorization header.
   *
   * @param {Object} state - Vuex state.
   * @param {Object} user - Authorized user details.
   *
   * @return void
   */
  [PROFILE_MUTATION](state, user) {
    state.auth.user = user;
  },

  /**************************************
   * USER INTERACTION RELATED MUTATIONS *
   *************************************/
  /**
   * Set login modal visible param to true.
   *
   * @param {Object} state
   * @returns {Object}
   */
  [OPEN_LOGIN_MODAL_MUTATION](state) {
    state.loginModal = {
      visible: true,
      params:  {},
    };
  },

  /**
   * Set login modal visible param to false.
   *
   * @param {Object} state
   * @param {Boolean} closeResult Result of interaction with user. TRUE when user was successfully logged in
   * @returns {Object}
   */
  [CLOSE_LOGIN_MODAL_MUTATION](
    state,
    closeResult, // eslint-disable-line no-unused-vars
  ) {
    state.loginModal = {
      visible: false,
      params:  {},
    };
  },

  /**
   * Open model confirm modal.
   *
   * @param {Object} state
   * @param {Object} params Modal window params
   * @returns {Object}
   */
  [OPEN_CONFIRMATION_MODAL_MUTATION](state, params) {
    state.confirmModal = {
      visible: true,
      params,
    };
  },

  /**
   * Close model confirm modal.
   *
   * @param {Object} state
   * @param {Boolean} closeResult User confirmation result
   * @returns {Object}
   *
   */
  [CLOSE_CONFIRMATION_MODAL_MUTATION](
    state,
    closeResult, // eslint-disable-line no-unused-vars
  ) {
    state.confirmModal = {
      visible: false,
      params:  {},
    };
  },

  /**
   * Open error notification modal.
   *
   * @param {Object} state
   * @param {Object} params
   * @returns {Object}
   */
  [OPEN_ERROR_NOTIFICATION_MODAL_MUTATION](state, params) {
    state.errorNotificationModal = {
      visible: true,
      params,
    };
  },

  /**
   * Close error notification modal.
   *
   * @param {Object} state
   * @returns {Object}
   */
  [CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION](state) {
    state.errorNotificationModal = {
      visible: false,
      params:  {},
    };
  },

  /**
   * Adds new alerts to list of alerts to display.
   *
   * @param {Object} state
   * @param {{message: string, type: string}} alert Alert to show
   * @returns {Object}
   */
  [ADD_ALERT_MUTATION](state, alert) {
    state.alerts.push(alert);
  },

  /**
   * Removes alerts from list of alerts to display.
   *
   * @param {Object} state
   * @param {{message: string, type: string}} alert Alert to fogot
   * @returns {Object}
   */
  [FORGOT_ALERT_MUTATION](state, alert) {
    const { alerts } = state;
    const alertPosition = alerts.indexOf(alert);

    if (alertPosition !== -1) {
      alerts.splice(alertPosition, 1);
      state.alerts = alerts;
    }
  },
};

/**
 * List of simple mutations that sets same value to storage as it's names.
 *
 * @type {string[]}
 */
const simpleMutations = [
  CARD_TYPES_MUTATION,
  TARIFF_PERIODS_MUTATION,
  TARIFFS_MUTATION,
  COMPANIES_MUTATION,
  USERS_MUTATION,
  ROLES_MUTATION,
  ROUTES_MUTATION,
  BUSES_MUTATION,
  DRIVERS_MUTATION,
  CARDS_MUTATION,
  DRIVERS_CARDS_MUTATION,
];

simpleMutations.forEach(mutationType => {
  mutations[mutationType] = (state, data) => { state[mutationType] = data; };
});

export default mutations;

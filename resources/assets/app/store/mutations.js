import jwtDecode from 'jwt-decode';

export const LOGIN_MUTATION = 'loginMutation';
export const LOGOUT_MUTATION = 'logoutMutation';
export const OPEN_CONFIRMATION_MODAL_MUTATION =
  'openConfirmationModalMutation';
export const CLOSE_CONFIRMATION_MODAL_MUTATION =
  'closeConfirmationModalMutation';
export const OPEN_ERROR_NOTIFICATION_MODAL_MUTATION =
  'openErrorNotificationModalMutation';
export const CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION =
  'closeErrorNotificationModalMutation';
export const OPEN_LOGIN_MODAL_MUTATION = 'openLoginModalMutation';
export const CLOSE_LOGIN_MODAL_MUTATION = 'closeLoginModalMutation';
export const ADD_ALERT_MUTATION = 'addAlertMutation';
export const FORGOT_ALERT_MUTATION = 'forgotAlertMutation';
export const PROFILE_MUTATION = 'profileMutation';
export const CARD_TYPES_MUTATION = 'cardTypesMutation';
export const TARIFF_PERIODS_MUTATION = 'tariffPeriodsMutation';
export const TARIFFS_MUTATION = 'tariffsMutation';
export const COMPANIES_MUTATION = 'companiesMutation';
export const USERS_MUTATION = 'usersMutation';

export default {
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

  /********************************
   * CARD TYPES RELATED MUTATIONS *
   *******************************/
  /**
   * Stores list of card types in application.
   *
   * @param {Object} state - Vuex state.
   * @param {Object[]} cardTypes - List of card types.
   *
   * @return void
   */
  [CARD_TYPES_MUTATION](state, cardTypes) {
    state.cardTypes = cardTypes;
  },

  /*****************************
   * TARIFFS RELATED MUTATIONS *
   ****************************/
  /**
   * Stores list of tariff periods in application.
   *
   * @param {Object} state - Vuex state.
   * @param {Object[]} tariffPeriods - List of tariff periods.
   *
   * @return void
   */
  [TARIFF_PERIODS_MUTATION](state, tariffPeriods) {
    state.tariffPeriods = tariffPeriods;
  },
  /**
   * Stores list of tariffs in application.
   *
   * @param {Object} state - Vuex state.
   * @param {Object[]} tariffs - List of tariff.
   *
   * @return void
   */
  [TARIFFS_MUTATION](state, tariffs) {
    state.tariffs = tariffs;
  },

  /*******************************
   * COMPANIES RELATED MUTATIONS *
   ******************************/
  /**
   * Stores list of companies in application.
   *
   * @param {Object} state - Vuex state.
   * @param {Object[]} companies - List of companies.
   *
   * @return void
   */
  [COMPANIES_MUTATION](state, companies) {
    state.companies = companies;
  },

  /***************************
   * USERS RELATED MUTATIONS *
   **************************/
  /**
   * Stores list of users in application.
   *
   * @param {Object} state - Vuex state.
   * @param {Object[]} users - List of users.
   *
   * @return void
   */
  [COMPANIES_MUTATION](state, users) {
    state.users = users;
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

    alerts.splice(alerts.indexOf(alert), 1);
    state.alerts = alerts;
  },
};

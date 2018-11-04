export const TOKEN_GETTER = 'tokenGetter';
export const AUTHENTICATED_GETTER = 'authenticatedGetter';
export const LOGIN_MODAL_GETTER = 'loginModal';
export const CONFIRM_MODAL_GETTER = 'confirmModal';
export const ERROR_NOTIFICATION_MODAL_GETTER = 'errorNotificationModal';
export const ALERTS_GETTER = 'alerts';
export const PROFILE_GETTER = 'profile';
export const CARD_TYPES_GETTER = 'cardTypes';
export const TARIFF_PERIODS_GETTER = 'tariffPeriods';
export const TARIFFS_GETTER = 'tariffs';
export const COMPANIES_GETTER = 'companies';
export const USERS_GETTER = 'users';

export default {
  /**********************************
   * AUTHENTICATION RELATED GETTERS *
   **********************************/
  /**
   * Returns authentication token.
   *
   * @param {Object} state - Vuex state.
   *
   * @return {string|null}
   */
  [TOKEN_GETTER]: state => state.auth.token,

  /**
   * Returns true if user is authenticated.
   *
   * @param {Object} state - Vuex state.
   *
   * @return {boolean}
   */
  [AUTHENTICATED_GETTER]: state => state.auth.authenticated,

  /********************************
   * USER PROFILE RELATED GETTERS *
   *******************************/
  [PROFILE_GETTER]: state => state.auth.user,

  /******************************
   * CARD TYPES RELATED GETTERS *
   *****************************/
  [CARD_TYPES_GETTER]: state => state.cardTypes,

  /**************************
   * TARIFF RELATED GETTERS *
   *************************/
  [TARIFF_PERIODS_GETTER]: state => state.tariffPeriods,

  [TARIFFS_GETTER]: state => state.tariffs,

  /*****************************
   * COMPANIES RELATED GETTERS *
   ****************************/
  [COMPANIES_GETTER]: state => state.companies,

  /*************************
   * USERS RELATED GETTERS *
   ************************/
  [USERS_GETTER]: state => state.users,

  /************************************
   * USER INTERACTION RELATED GETTERS *
   ************************************/
  /**
   * Whether it is necessary to show login modal.
   *
   * @param {Object} state
   * @returns {boolean}
   */
  [LOGIN_MODAL_GETTER]: state => state.loginModal,

  /**
   * Returns confirm modal visibility and params.
   *
   * @param {Object} state
   * @returns {{visible: boolean, params: Object}}
   */
  [CONFIRM_MODAL_GETTER]: state => state.confirmModal,

  /**
   * Returns error notification modal visible and params.
   *
   * @param {Object} state
   * @returns {{visible: boolean, params: Object}}
   */
  [ERROR_NOTIFICATION_MODAL_GETTER]: state => state.errorNotificationModal,

  /**
   * Returns alerts that should be displayed to user.
   *
   * @param {Object} state
   *
   * @returns {Object}
   */
  [ALERTS_GETTER]: state => state.alerts,

};

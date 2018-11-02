export const TOKEN_GETTER = 'tokenGetter';
export const AUTHENTICATED_GETTER = 'authenticatedGetter';
export const LOGIN_MODAL_GETTER = 'loginModal';
export const DELETE_MODAL_GETTER = 'deleteModal';
export const ERROR_NOTIFICATION_MODAL_GETTER = 'errorNotificationModal';
export const PROFILE_GETTER = 'profile';
export const CARD_TYPES_GETTER = 'cardTypes';

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

  /************************************
   * USER INTERACTION RELATED GETTERS *
   ************************************/
  /**
   * Whether it is necessary to show login modal.
   *
   * @param {Object} state
   * @returns {boolean}
   */
  [LOGIN_MODAL_GETTER]:              state => state.loginModal,
  /**
   * Returns delete modal visible and params.
   *
   * @param {Object} state
   * @returns {{visible: boolean, params: Object}}
   */
  [DELETE_MODAL_GETTER]:             state => state.deleteModal,
  /**
   * Returns error notification modal visible and params.
   *
   * @param {Object} state
   * @returns {{visible: boolean, params: Object}}
   */
  [ERROR_NOTIFICATION_MODAL_GETTER]: state => state.errorNotificationModal,

};

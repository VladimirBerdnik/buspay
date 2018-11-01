import jwtDecode from 'jwt-decode';

export const LOGIN_MUTATION = 'loginMutation';
export const LOGOUT_MUTATION = 'logoutMutation';
export const OPEN_DELETE_CONFIRMATION_MODAL_MUTATION =
  'openDeleteConfirmationModalMutation';
export const CLOSE_DELETE_CONFIRMATION_MODAL_MUTATION =
  'closeDeleteConfirmationModalMutation';
export const OPEN_ERROR_NOTIFICATION_MODAL_MUTATION =
  'openErrorNotificationModalMutation';
export const CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION =
  'closeErrorNotificationModalMutation';
export const OPEN_LOGIN_MODAL_MUTATION = 'openLoginModalMutation';
export const CLOSE_LOGIN_MODAL_MUTATION = 'closeLoginModalMutation';
export const PROFILE_MUTATION = 'profileMutation';
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
   * Open model delete confirm modal.
   *
   * @param {Object} state
   * @param {Object} params Modal window params
   * @returns {Object}
   */
  [OPEN_DELETE_CONFIRMATION_MODAL_MUTATION](state, params) {
    state.deleteModal = {
      visible: true,
      params,
    };
  },

  /**
   * Close model delete confirm modal.
   *
   * @param {Object} state
   * @param {Boolean} closeResult User delete confirmation result
   * @returns {Object}
   *
   */
  [CLOSE_DELETE_CONFIRMATION_MODAL_MUTATION](
    state,
    closeResult, // eslint-disable-line no-unused-vars
  ) {
    state.deleteModal = {
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
};

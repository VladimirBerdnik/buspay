import jwtDecode from 'jwt-decode';

export const LOGIN_MUTATION = 'loginMutation';
export const LOGOUT_MUTATION = 'logoutMutation';

export default {
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
};

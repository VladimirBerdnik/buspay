export const TOKEN_GETTER = 'tokenGetter';
export const AUTHENTICATED_GETTER = 'authenticatedGetter';

export default {
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
};

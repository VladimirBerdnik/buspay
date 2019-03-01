import { mapMutations, mapGetters } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import {
  LOGIN_MUTATION,
  LOGOUT_MUTATION,
} from '../store/mutations';
import { TOKEN_GETTER, AUTHENTICATED_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    loginMutation:  LOGIN_MUTATION,
    logoutMutation: LOGOUT_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ token: TOKEN_GETTER, auth: AUTHENTICATED_GETTER }),

  /**
   * Attempt user login and return token.
   *
   * @param {string} email - User email.
   * @param {string} password - User password.
   *
   * @return {string}
   *
   * @throws Error
   */
  async login(email, password) {
    const response = await axios.post('/auth', { email, password });

    this.loginMutation(response.data.token);

    return response.data.token;
  },

  /**
   * Logout user.
   *
   * @return void
   *
   * @throws Error
   */
  async logout() {
    try {
      await axios.delete('/auth');
    } finally {
      this.logoutMutation();
    }
  },

  /**
   * Refresh expired token.
   *
   * @return {string}
   *
   * @throws Error
   */
  async refreshToken() {
    const response = await axios.put('/auth');

    this.loginMutation(response.data.token);

    return response.data.token;
  },

  /**
   * Returns TRUE if the current user is authenticated.
   *
   * @return {boolean}
   */
  isAuthenticated() {
    return this.auth();
  },

  /**
   * Returns auth token.
   *
   * @return {string|null}
   */
  getToken() {
    return this.token();
  },
};

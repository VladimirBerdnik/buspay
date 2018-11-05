import { mapMutations, mapGetters } from 'vuex';
import UserInteractionService from './UserInteractionService';
import axios from '../config/axios';
import store from '../store/index';
import { PROFILE_MUTATION } from '../store/mutations';
import { PROFILE_GETTER } from '../store/getters';
import AuthService from './AuthService';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    profileMutation: PROFILE_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ profile: PROFILE_GETTER }),

  /**
   * Reads authenticated user profile details.
   *
   * @return {User}
   *
   * @throws Error
   */
  async readProfile() {
    UserInteractionService.withAuth(async () => {
      const response = await axios.get('/me/');

      this.profileMutation(response.data || {});

      return response.data;
    }, AuthService.logout);
  },

  /**
   * Returns authenticated user details.
   *
   * @return {User}
   */
  getProfile() {
    return this.profile();
  },
};

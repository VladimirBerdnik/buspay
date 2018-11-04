import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { ROLES_MUTATION } from '../store/mutations';
import { ROLES_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    rolesMutation: ROLES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ roles: ROLES_GETTER }),

  /**
   * Reads roles list.
   *
   * @return {Role[]}
   *
   * @throws Error
   */
  async readRoles() {
    const response = await axios.get('/roles/');

    this.rolesMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of roles.
   *
   * @param {boolean} forceFresh Force service to read actual information
   *
   * @return {Role[]}
   */
  getRoles(forceFresh = false) {
    if (forceFresh) {
      this.readRoles();
    }

    return this.roles();
  },
};

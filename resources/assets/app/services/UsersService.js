import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import roles from '../policies/roles';
import { USERS_MUTATION } from '../store/mutations';
import { USERS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation: USERS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ users: USERS_GETTER }),

  /**
   * Reads users list.
   *
   * @return {User[]}
   *
   * @throws Error
   */
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/users');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves user. Creates new or updates existing.
   *
   * @param {User} user User to save
   *
   * @return {User}
   */
  async save(user) {
    let response = null;

    if (user.id) {
      response = await axios.put(`/users/${user.id}`, user);
    } else {
      response = await axios.post('/users', user);
    }

    return response.data;
  },

  /**
   * Deletes user.
   *
   * @param {User} user User to delete
   *
   * @return {*}
   */
  delete(user) {
    return axios.delete(`/users/${user.id}`);
  },

  /**
   * Returns list of users.
   *
   * @return {User[]}
   */
  get() {
    return this.users();
  },

  /**
   * Returns whether user with given role should be with company or not.
   *
   * @param {number} roleId Role identifier to check
   *
   * @return {boolean} Witch company or not
   */
  roleWithCompany(roleId) {
    return [ roles.ADMIN, roles.GOVERNMENT, roles.SUPPORT ].indexOf(roleId) === -1;
  },
};

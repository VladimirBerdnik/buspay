import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { USERS_MUTATION } from '../store/mutations';
import { USERS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    usersMutation: USERS_MUTATION,
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
  async readUsers() {
    const response = await axios.get('/users/');

    this.usersMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves user. Creates new or updates existing.
   *
   * @param {User} user User to save
   *
   * @return {User}
   */
  async saveUser(user) {
    let response = null;

    if (user.id) {
      response = await axios.put(`/users/${user.id}/`, user);
    } else {
      response = await axios.post('/users/', user);
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
    return axios.delete(`/users/${user.id}/`);
  },

  /**
   * Returns list of users.
   *
   * @param {boolean} forceFresh Force service to read actual information
   *
   * @return {User[]}
   */
  getUsers(forceFresh = false) {
    if (forceFresh) {
      this.readUsers();
    }

    return this.users();
  },
};

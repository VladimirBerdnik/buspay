import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { ROUTES_MUTATION } from '../store/mutations';
import { ROUTES_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    routesMutation: ROUTES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ routes: ROUTES_GETTER }),

  /**
   * Reads routes list.
   *
   * @return {Route[]}
   *
   * @throws Error
   */
  async read() {
    const response = await axios.get('/routes/');

    this.routesMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves route. Creates new or updates existing.
   *
   * @param {Route} route Route to save
   *
   * @return {Route}
   */
  async save(route) {
    let response = null;

    if (route.id) {
      response = await axios.put(`/routes/${route.id}/`, route);
    } else {
      response = await axios.post('/routes/', route);
    }

    return response.data;
  },

  /**
   * Deletes route.
   *
   * @param {Route} route Route to delete
   *
   * @return {*}
   */
  delete(route) {
    return axios.delete(`/routes/${route.id}/`);
  },

  /**
   * Returns list of routes.
   *
   * @return {Route[]}
   */
  get() {
    return this.routes();
  },
};

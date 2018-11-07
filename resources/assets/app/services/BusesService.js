import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { BUSES_MUTATION } from '../store/mutations';
import { BUSES_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    busesMutation: BUSES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ buses: BUSES_GETTER }),

  /**
   * Reads buses list.
   *
   * @return {Bus[]}
   *
   * @throws Error
   */
  async read() {
    const response = await axios.get('/buses/');

    this.busesMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves bus. Creates new or updates existing.
   *
   * @param {Bus} bus Bus to save
   *
   * @return {Bus}
   */
  async save(bus) {
    let response = null;

    if (bus.id) {
      response = await axios.put(`/buses/${bus.id}/`, bus);
    } else {
      response = await axios.post('/buses/', bus);
    }

    return response.data;
  },

  /**
   * Deletes bus.
   *
   * @param {Bus} bus Bus to delete
   *
   * @return {*}
   */
  delete(bus) {
    return axios.delete(`/buses/${bus.id}/`);
  },

  /**
   * Returns list of buses.
   *
   * @return {Bus[]}
   */
  get() {
    return this.buses();
  },
};

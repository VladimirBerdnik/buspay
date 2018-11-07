import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { DRIVERS_MUTATION } from '../store/mutations';
import { DRIVERS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    driversMutation: DRIVERS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ drivers: DRIVERS_GETTER }),

  /**
   * Reads drivers list.
   *
   * @return {Driver[]}
   *
   * @throws Error
   */
  async read() {
    const response = await axios.get('/drivers/');

    this.driversMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves driver. Creates new or updates existing.
   *
   * @param {Driver} driver Driver to save
   *
   * @return {Driver}
   */
  async save(driver) {
    let response = null;

    if (driver.id) {
      response = await axios.put(`/drivers/${driver.id}/`, driver);
    } else {
      response = await axios.post('/drivers/', driver);
    }

    return response.data;
  },

  /**
   * Deletes driver.
   *
   * @param {Driver} driver Driver to delete
   *
   * @return {*}
   */
  delete(driver) {
    return axios.delete(`/drivers/${driver.id}/`);
  },

  /**
   * Returns list of drivers.
   *
   * @return {Driver[]}
   */
  get() {
    return this.drivers();
  },
};

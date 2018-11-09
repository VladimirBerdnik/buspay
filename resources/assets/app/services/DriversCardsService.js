import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { DRIVERS_CARDS_MUTATION } from '../store/mutations';
import { DRIVERS_CARDS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    driversCardsMutation: DRIVERS_CARDS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ driversCards: DRIVERS_CARDS_GETTER }),

  /**
   * Reads drivers cards list.
   *
   * @return {Driver[]}
   *
   * @throws Error
   */
  async read() {
    const response = await axios.get('/cards/drivers/');

    this.driversCardsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of drivers cards.
   *
   * @return {Driver[]}
   */
  get() {
    return this.driversCards();
  },
};

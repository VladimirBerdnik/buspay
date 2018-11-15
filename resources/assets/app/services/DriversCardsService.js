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
    itemsMutation: DRIVERS_CARDS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ driversCards: DRIVERS_CARDS_GETTER }),

  /**
   * Reads drivers cards list.
   *
   * @return {Card[]}
   *
   * @throws Error
   */
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/cards/drivers/');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of drivers cards.
   *
   * @return {Card[]}
   */
  get() {
    return this.driversCards();
  },
};

import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { TARIFFS_MUTATION } from '../store/mutations';
import { TARIFFS_GETTER } from '../store/getters';

/**
 * Service that can retrieve list of tariffs.
 */
export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation: TARIFFS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ tariffs: TARIFFS_GETTER }),

  /**
   * Reads tariffs list.
   *
   * @return {Tariff[]}
   *
   * @throws Error
   */
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/tariffs');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of tariffs.
   *
   * @return {Tariff[]}
   */
  get() {
    return this.tariffs();
  },
};

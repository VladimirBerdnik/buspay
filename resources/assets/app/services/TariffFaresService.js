import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { TARIFF_FARES_MUTATION } from '../store/mutations';
import { TARIFF_FARES_GETTER } from '../store/getters';

/**
 * Service that can retrieve list of tariffs with tariff fares for requested tariff period.
 */
export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation: TARIFF_FARES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ tariffFares: TARIFF_FARES_GETTER }),

  /**
   * Reads tariffs list.
   *
   * @param {TariffPeriod} tariffPeriod Period of tariffs activity
   *
   * @return {Tariff[]}
   *
   * @throws Error
   */
  async read(tariffPeriod) {
    this.itemsMutation([]);

    const response = await axios.get(`/tariffPeriods/${tariffPeriod.id}/tariffs`);

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of tariffs.
   *
   * @return {Tariff[]}
   */
  get() {
    return this.tariffFares();
  },
};

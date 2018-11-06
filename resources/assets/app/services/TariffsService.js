import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { TARIFFS_MUTATION } from '../store/mutations';
import { TARIFFS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    tariffsMutation: TARIFFS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ tariffs: TARIFFS_GETTER }),

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
    const response = await axios.get(`/tariffPeriods/${tariffPeriod.id}/tariffs/`);

    this.tariffsMutation(response.data.results || []);

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

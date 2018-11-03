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
  async readTariffs(tariffPeriod) {
    const response = await axios.get(`/tariffPeriods/${tariffPeriod.id}/tariffs/`);

    this.tariffsMutation(response.data);

    return response.data;
  },

  /**
   * Returns list of tariffs.
   *
   * @param {boolean} forceFresh Force service to read actual information
   *
   * @return {Tariff[]}
   */
  getTariffs(forceFresh = false) {
    if (forceFresh) {
      this.readTariffs();
    }

    return this.tariffs();
  },
};

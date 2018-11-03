import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { TARIFF_PERIODS_MUTATION } from '../store/mutations';
import { TARIFF_PERIODS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    tariffPeriodsMutation: TARIFF_PERIODS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ tariffPeriods: TARIFF_PERIODS_GETTER }),

  /**
   * Reads tariff periods list.
   *
   * @return {TariffPeriod[]}
   *
   * @throws Error
   */
  async readTariffPeriods() {
    const response = await axios.get('/tariffPeriods/');

    this.tariffPeriodsMutation(response.data);

    return response.data;
  },

  /**
   * Returns list of tariff periods.
   *
   * @param {boolean} forceFresh Force service to read actual information
   *
   * @return {TariffPeriod[]}
   */
  getTariffPeriods(forceFresh = false) {
    if (forceFresh) {
      this.readTariffPeriods();
    }

    return this.tariffPeriods();
  },
};

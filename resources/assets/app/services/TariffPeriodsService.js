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
    itemsMutation: TARIFF_PERIODS_MUTATION,
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
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/tariffPeriods/');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of tariff periods.
   *
   * @return {TariffPeriod[]}
   */
  get() {
    return this.tariffPeriods();
  },
};

import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { COMPANIES_MUTATION } from '../store/mutations';
import { COMPANIES_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    companiesMutation: COMPANIES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ companies: COMPANIES_GETTER }),

  /**
   * Reads companies list.
   *
   * @return {Company[]}
   *
   * @throws Error
   */
  async readCompanies() {
    const response = await axios.get('/companies/');

    this.companiesMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of companies.
   *
   * @param {boolean} forceFresh Force service to read actual information
   *
   * @return {Company[]}
   */
  getCompanies(forceFresh = false) {
    if (forceFresh) {
      this.readCompanies();
    }

    return this.companies();
  },
};

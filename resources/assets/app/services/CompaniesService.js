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
    itemsMutation: COMPANIES_MUTATION,
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
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/companies/');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Saves company. Creates new or updates existing.
   *
   * @param {Company} company Company to save
   *
   * @return {Company}
   */
  async save(company) {
    let response = null;

    if (company.id) {
      response = await axios.put(`/companies/${company.id}/`, company);
    } else {
      response = await axios.post('/companies/', company);
    }

    return response.data;
  },

  /**
   * Deletes company.
   *
   * @param {Company} company Company to delete
   *
   * @return {*}
   */
  delete(company) {
    return axios.delete(`/companies/${company.id}/`);
  },

  /**
   * Returns list of companies.
   *
   * @return {Company[]}
   */
  get() {
    return this.companies();
  },
};

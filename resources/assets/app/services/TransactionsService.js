import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { TRANSACTIONS_MUTATION, TRANSACTIONS_PAGINATION_MUTATION } from '../store/mutations';
import { TRANSACTIONS_GETTER, TRANSACTIONS_PAGINATION_GETTER } from '../store/getters';
import DownloadService from './DownloadService';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation:           TRANSACTIONS_MUTATION,
    itemsPaginationMutation: TRANSACTIONS_PAGINATION_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({
    items:           TRANSACTIONS_GETTER,
    itemsPagination: TRANSACTIONS_PAGINATION_GETTER,
  }),

  /**
   * Reads transactions list.
   *
   * @param {Object} params Request parameters such as filters, pagination and sorting details
   *
   * @return {Transaction[]}
   *
   * @throws Error
   */
  async read(params) {
    this.itemsMutation([]);

    const response = await axios.get('/transactions/', { params });

    this.itemsMutation(response.data.results || []);
    this.itemsPaginationMutation(response.data.pagination || {});

    return response.data;
  },

  /**
   * Initiates transactions list downloading.
   *
   * @param {Object} params Request parameters such as filters and sorting details
   *
   * @throws Error
   */
  async export(params) {
    const response = await axios.get('/transactions/export', { params, responseType: 'blob' });

    DownloadService.downloadAsFile(response.data, 'transactions.csv');
  },

  /**
   * Returns list of transactions.
   *
   * @return {Transaction[]}
   */
  get() {
    return this.items();
  },

  /**
   * Returns currently loaded list of transactions pagination information.
   *
   * @return {PaginationInfo}
   */
  getPaginationInfo() {
    return this.itemsPagination();
  },
};

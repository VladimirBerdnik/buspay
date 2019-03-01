import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { REPLENISHMENTS_MUTATION, REPLENISHMENTS_PAGINATION_MUTATION } from '../store/mutations';
import { REPLENISHMENTS_GETTER, REPLENISHMENTS_PAGINATION_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation:           REPLENISHMENTS_MUTATION,
    itemsPaginationMutation: REPLENISHMENTS_PAGINATION_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({
    items:           REPLENISHMENTS_GETTER,
    itemsPagination: REPLENISHMENTS_PAGINATION_GETTER,
  }),

  /**
   * Reads replenishments list.
   *
   * @param {Object} params Request parameters such as filters, pagination and sorting details
   *
   * @return {Replenishment[]}
   *
   * @throws Error
   */
  async read(params) {
    this.itemsMutation([]);

    const response = await axios.get('/replenishments', { params });

    this.itemsMutation(response.data.results || []);
    this.itemsPaginationMutation(response.data.pagination || {});

    return response.data;
  },

  /**
   * Returns list of replenishments.
   *
   * @return {Replenishment[]}
   */
  get() {
    return this.items();
  },

  /**
   * Returns currently loaded list of replenishments pagination information.
   *
   * @return {PaginationInfo}
   */
  getPaginationInfo() {
    return this.itemsPagination();
  },
};

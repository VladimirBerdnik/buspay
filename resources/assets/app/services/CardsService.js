import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { CARDS_MUTATION, CARDS_PAGINATION_MUTATION } from '../store/mutations';
import { CARDS_GETTER, CARDS_PAGINATION_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation:           CARDS_MUTATION,
    itemsPaginationMutation: CARDS_PAGINATION_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({
    items:           CARDS_GETTER,
    itemsPagination: CARDS_PAGINATION_GETTER,
  }),

  /**
   * Reads cards list.
   *
   * @param {Object} params Request parameters such as filters, pagination and sorting details
   *
   * @return {Card[]}
   *
   * @throws Error
   */
  async read(params) {
    this.itemsMutation([]);

    const response = await axios.get('/cards', { params });

    this.itemsMutation(response.data.results || []);
    this.itemsPaginationMutation(response.data.pagination || {});

    return response.data;
  },

  /**
   * Returns list of cards.
   *
   * @return {Card[]}
   */
  get() {
    return this.items();
  },

  /**
   * Returns currently loaded list of cards pagination information.
   *
   * @return {PaginationInfo}
   */
  getPaginationInfo() {
    return this.itemsPagination();
  },
};

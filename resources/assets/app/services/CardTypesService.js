import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { CARD_TYPES_MUTATION } from '../store/mutations';
import { CARD_TYPES_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation: CARD_TYPES_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ cardTypes: CARD_TYPES_GETTER }),

  /**
   * Reads card types list.
   *
   * @return {CardType[]}
   *
   * @throws Error
   */
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/cardTypes');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of card types.
   *
   * @return {CardType[]}
   */
  get() {
    return this.cardTypes();
  },
};

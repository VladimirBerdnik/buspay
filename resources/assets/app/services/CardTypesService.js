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
    cardTypesMutation: CARD_TYPES_MUTATION,
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
  async readCardTypes() {
    const response = await axios.get('/cardTypes/');

    this.cardTypesMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Returns list of card types.
   *
   * @return {CardType[]}
   */
  getCardTypes() {
    return this.cardTypes();
  },
};

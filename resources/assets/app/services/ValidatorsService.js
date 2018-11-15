import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { VALIDATORS_MUTATION } from '../store/mutations';
import { VALIDATORS_GETTER } from '../store/getters';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation: VALIDATORS_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ validators: VALIDATORS_GETTER }),

  /**
   * Reads validators list.
   *
   * @return {Validator[]}
   *
   * @throws Error
   */
  async read() {
    this.itemsMutation([]);

    const response = await axios.get('/validators/');

    this.itemsMutation(response.data.results || []);

    return response.data;
  },

  /**
   * Updates validator details.
   *
   * @param {Validator} validator Validator to save
   *
   * @return {Validator}
   */
  async save(validator) {
    let response = null;

    response = await axios.put(`/validators/${validator.id}/`, validator);

    return response.data;
  },

  /**
   * Returns list of validators.
   *
   * @return {Validator[]}
   */
  get() {
    return this.validators();
  },
};

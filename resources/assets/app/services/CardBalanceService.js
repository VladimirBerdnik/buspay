import axios from '../config/axios';

export default {
  /**
   * Reads card balance total.
   *
   * @param {Number} cardNumber Card number to retrieve balance totals for
   *
   * @return {Number}
   *
   * @throws Error
   */
  async totals(cardNumber) {
    const response = await axios.get(`/cardBalance/${cardNumber}/total`);

    return response.data.total;
  },

  /**
   * Returns list of cards.
   *
   * @return {Card[]}
   */
  get() {
    return this.items();
  },
};

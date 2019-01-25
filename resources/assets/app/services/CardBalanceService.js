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
   * Reads card balance transactions.
   *
   * @param {Number} cardNumber Card number to retrieve balance totals for
   *
   * @return {CardBalanceTransactionData[]}
   *
   * @throws Error
   */
  async transactions(cardNumber) {
    const response = await axios.get(`/cardBalance/${cardNumber}/transactions`);

    return response.data.results;
  },
};

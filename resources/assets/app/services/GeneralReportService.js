import axios from '../config/axios';

/**
 * Service that can retrieve general report data in requested format.
 */
export default {
  reportData: [],

  /**
   * Reads general report data in requested format.
   *
   * @return {Object[]}
   *
   * @throws Error
   */
  async read(params) {
    this.reportData = [];

    const response = await axios.get('/reports/general/', { params });

    this.reportData = response.data.results || [];

    return this.get();
  },

  get() {
    return this.reportData;
  },
};

import axios from '../config/axios';

/**
 * Service that can retrieve general report data in requested format.
 */
export default {

  /**
   * Reads general report data in requested format.
   *
   * @return {Object[]}
   *
   * @throws Error
   */
  async getData(params) {
    this.itemsMutation([]);

    const response = await axios.get('/reports/general/', { params });

    this.itemsMutation(response.data.results || []);

    return response.data;
  },
};

import axios from '../config/axios';
import DownloadService from './DownloadService';

/**
 * Service that can retrieve general report data in requested format.
 */
export default {
  reportData: [],

  /**
   * Reads general report data in requested format.
   *
   * @param {Object} params Request parameters such as filters and sorting details
   *
   * @return {Object[]}
   *
   * @throws Error
   */
  async read(params) {
    this.reportData = [];

    const response = await axios.get('/reports/general', { params });

    this.reportData = response.data.results || [];

    return this.get();
  },

  /**
   * Initiates general report data downloading.
   *
   * @param {Object} params Request parameters such as filters and sorting details
   *
   * @throws Error
   */
  async export(params) {
    const response = await axios.get('/reports/general/export', { params, responseType: 'blob' });

    DownloadService.downloadAsFile(response.data, 'report.csv');
  },

  get() {
    return this.reportData;
  },
};

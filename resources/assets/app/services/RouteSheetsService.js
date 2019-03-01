import { mapGetters, mapMutations } from 'vuex';
import axios from '../config/axios';
import store from '../store/index';
import { ROUTE_SHEETS_MUTATION, ROUTE_SHEETS_PAGINATION_MUTATION } from '../store/mutations';
import { ROUTE_SHEETS_GETTER, ROUTE_SHEETS_PAGINATION_GETTER } from '../store/getters';
import DownloadService from './DownloadService';

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    itemsMutation:           ROUTE_SHEETS_MUTATION,
    itemsPaginationMutation: ROUTE_SHEETS_PAGINATION_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({
    items:           ROUTE_SHEETS_GETTER,
    itemsPagination: ROUTE_SHEETS_PAGINATION_GETTER,
  }),

  /**
   * Reads route sheets list.
   *
   * @param {Object} params Request parameters such as filters, pagination and sorting details
   *
   * @return {RouteSheet[]}
   *
   * @throws Error
   */
  async read(params) {
    this.itemsMutation([]);

    const response = await axios.get('/route_sheets', { params });

    this.itemsMutation(response.data.results || []);
    this.itemsPaginationMutation(response.data.pagination || {});

    return response.data;
  },


  /**
   * Initiates route sheets list downloading.
   *
   * @param {Object} params Request parameters such as filters and sorting details
   *
   * @throws Error
   */
  async export(params) {
    const response = await axios.get('/route_sheets/export', { params, responseType: 'blob' });

    DownloadService.downloadAsFile(response.data, 'routeSheets.csv');
  },

  /**
   * Saves route sheet. Creates new or updates existing.
   *
   * @param {RouteSheet} routeSheet Route sheet to save to save
   *
   * @return {RouteSheet}
   */
  async save(routeSheet) {
    let response = null;

    if (routeSheet.id) {
      response = await axios.put(`/route_sheets/${routeSheet.id}`, routeSheet);
    } else {
      response = await axios.post('/route_sheets', routeSheet);
    }

    return response.data;
  },

  /**
   * Returns list of route sheets.
   *
   * @return {RouteSheet[]}
   */
  get() {
    return this.items();
  },

  /**
   * Deletes route sheet.
   *
   * @param {RouteSheet} routeSheet Route sheet to delete
   *
   * @return {*}
   */
  delete(routeSheet) {
    return axios.delete(`/route_sheets/${routeSheet.id}`);
  },

  /**
   * Returns currently loaded list of route sheets pagination information.
   *
   * @return {PaginationInfo}
   */
  getPaginationInfo() {
    return this.itemsPagination();
  },
};

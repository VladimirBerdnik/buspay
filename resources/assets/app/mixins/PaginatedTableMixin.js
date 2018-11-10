import datatablesConfig from '../config/datatables';
/**
 * Mixin for page with list of items. Has methods to display and reload list of items.
 */
export default {
  data() {
    return {
      /**
       * Service that handles table items.
       */
      service:           null,
      datatablesConfig,
      loadingInProgress: false,
      totalItems:        null,
      totalPages:        null,
      pagination:        {
        descending:  false,
        page:        1,
        rowsPerPage: 15,
        sortBy:      'id',
      },
    };
  },
  computed: {
    /**
     * Handled items list.
     *
     * @return {Object[]}
     */
    items() {
      return this.service.get();
    },
  },
  watch: {
    pagination: {
      deep: true,
      handler(newPagination) {
        this.reloadTable(newPagination);
      },
    },
  },
  methods: {
    /**
     * Reloads table data.
     */
    async reloadTable(paginationDetails) {
      if (this.loadingInProgress) {
        return;
      }

      this.loadingInProgress = true;

      try {
        await this.service.read(paginationDetails);

        const paginationInfo = this.service.getPaginationInfo();

        this.pagination.page = paginationInfo.current_page;
        this.pagination.rowsPerPage = paginationInfo.per_page;
        this.totalItems = paginationInfo.total;
        this.totalPages = paginationInfo.total_pages;
      } catch (exception) {
        // no action required
      } finally {
        this.loadingInProgress = false;
      }
    },
  },
};

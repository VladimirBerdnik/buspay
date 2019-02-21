import _ from 'lodash';
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
      // List of strong equal filters values
      filters:    {},
      // Search string that should be applied to items
      search:     null,
      // Activity period filters
      activeFrom: null,
      activeTo:   null,
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
      handler() {
        this.debouncedReloadTable();
      },
    },
    filters: {
      deep: true,
      handler() {
        this.pagination.page = 1;
        this.debouncedReloadTable();
      },
    },
    search() {
      this.pagination.page = 1;
      this.debouncedReloadTable();
    },
    activeFrom() {
      this.pagination.page = 1;
      this.debouncedReloadTable();
    },
    activeTo() {
      this.pagination.page = 1;
      this.debouncedReloadTable();
    },
  },
  created() {
    this.debouncedReloadTable = _.debounce(this.reloadTable, 500);
  },
  methods: {
    /**
     * Reloads table data.
     */
    async reloadTable() {
      if (this.loadingInProgress) {
        return;
      }

      this.loadingInProgress = true;

      try {
        const params = Object.assign(
          {},
          this.pagination,
          { filters: this.filters },
          { search: this.search },
          { active_from: this.activeFrom },
          { active_to: this.activeTo }
        );

        await this.service.read(params);

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
    /**
     * Exports table data.
     */
    async exportRecords() {
      try {
        const params = Object.assign(
          {},
          this.pagination,
          { filters: this.filters },
          { search: this.search },
          { active_from: this.activeFrom },
          { active_to: this.activeTo }
        );

        await this.service.export(params);
      } catch (exception) {
        // no action required
      }
    },
  },
};

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
      service: null,
      datatablesConfig,
    };
  },
  computed: {
    /**
     * Handled items list.
     *
     * @return {Object[]}
     */
    items() { return this.service.get(); },
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      this.service.read();
    },
  },
};

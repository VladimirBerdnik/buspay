import datatablesConfig from '../config/datatables';
import stringUtils from '../utils/string';
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
    items() {
      let items = this.service.get();

      if (!this.filters) {
        return items;
      }

      Object.entries(this.filters).forEach(entry => {
        const [ filterField, value ] = entry;

        if (!value) {
          return;
        }
        items = items.filter(item => item[stringUtils.snakeCase(filterField)] === value);
      });

      return items;
    },
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

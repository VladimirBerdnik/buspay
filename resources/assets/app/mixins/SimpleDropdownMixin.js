import FormFieldMixin from './FormFieldMixin';

/**
 * Mixin for dropdown components with ability to select single value from list of values, retrieved from service.
 */
export default {
  mixins: [FormFieldMixin],
  props:  {
    fallbackItem: {
      type:     Object,
      default:  null,
      required: false,
    },
  },
  data() {
    return {
      itemId:    null,
      service:   null,
      itemText:  'name',
      itemValue: 'id',
      loading:   false,
    };
  },
  computed: {
    items() {
      const allItems = this.service.get();

      if (allItems.length === 0 && this.fallbackItem) {
        return [this.fallbackItem];
      }

      return allItems;
    },
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.changeValue(newValue);
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    itemId(newValue) {
      this.changeValue(newValue);
    },
  },
  methods: {
    /**
     * Changes component value and notifies parent about value changing.
     *
     * @param {number} newValue New component selected value
     */
    changeValue(newValue) {
      this.itemId = newValue;
      this.$emit('input', newValue);
    },
    /**
     * Loads updated list of items.
     */
    async reloadItems() {
      if (this.loading) {
        return;
      }
      this.loading = true;
      try {
        await this.service.read();
      } finally {
        this.loading = false;
      }
    },
  },
};

import FormFieldMixin from './FormFieldMixin';

/**
 * Mixin for dropdown components with ability to select single value from list of values, retrieved from service.
 */
export default {
  mixins: [FormFieldMixin],
  data() {
    return {
      itemId:    null,
      service:   null,
      itemText:  'name',
      itemValue: 'id',
    };
  },
  computed: {
    items() {
      return this.service.get();
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
    reloadItems() {
      this.service.read();
    },
  },
};

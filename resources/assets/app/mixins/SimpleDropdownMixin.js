/**
 * Mixin for dropdown components with ability to select single value.
 */
export default {
  props: {
    value: {
      type:    Number,
      default: null,
    },
    errorMessages: {
      type:    Array,
      default: () => [],
    },
    clearable: {
      type:    Boolean,
      default: true,
    },
    readonly: {
      type:    Boolean,
      default: false,
    },
  },
  data() {
    return {
      itemId:    null,
      service:   null,
      itemText:  'name',
      itemValue: 'id',
    };
  },
  computed: {
    items() { return this.service.get(); },
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.itemId = newValue;
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    itemId(newValue) {
      this.$emit('input', newValue);
    },
  },
};

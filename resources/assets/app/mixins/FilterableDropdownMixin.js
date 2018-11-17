/**
 * Mixin for dropdown components with ability to select single value among allowed prefiltered values.
 */
import SimpleDropdownMixin from './SimpleDropdownMixin';

export default {
  mixins: [SimpleDropdownMixin],
  watch:  {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    itemId(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * No any value should be selected when list of items is empty.
     *
     * @param {Object} newItems New filtered list of items
     */
    items(newItems) {
      if (!this.valueValid(this.itemId, newItems)) {
        this.changeValue(null);
      }
    },
  },
  methods: {
    /**
     * Check whether passed value is valid value to select.
     *
     * @param {number} value Value to check
     * @param {Object|null} allowedValues List of allowed items to check in. When not passed
     * component items will be taken
     *
     * @return {boolean}
     */
    valueValid(value, allowedValues = null) {
      return (allowedValues || this.items).some(item => item[this.itemValue] === value);
    },
  },
};

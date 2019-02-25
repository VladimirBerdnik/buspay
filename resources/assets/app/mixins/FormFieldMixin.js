/**
 * Mixin for custom form fields with common properties.
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
    label: {
      type:    String,
      default: null,
    },
    disabled: {
      type:    String,
      default: null,
    },
  },
};

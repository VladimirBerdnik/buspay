export default {
  props: {
    // Modal form has visibility mixin
    visible: {
      type:    Boolean,
      default: false,
    },
  },
  watch: {
    visible() {
      // When form visibility changed need to reset all validation errors for better user experience
      this.$validator.reset();
    },
  },
};

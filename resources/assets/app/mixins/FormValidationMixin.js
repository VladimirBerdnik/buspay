export default {
  methods: {
    /**
     * Takse API validation errors and integrates them into validator.
     *
     * @param {{field: string, messages: string[]}} errors Validation errors
     */
    handleValidationError(errors) {
      Object.values(errors).forEach(error => {
        Object.values(error.messages).forEach(message => {
          this.$validator.errors.add({ field: error.field, msg: message });
        });
      });
    },
  },
};

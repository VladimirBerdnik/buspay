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
    /**
     * Checks whether error is validation error.
     *
     * @param error
     * @return {boolean} Validation error or not
     */
    isValidationError(error) {
      const BAD_REQUEST_RESPONSE_STATUS = 400;

      return error
        && error.response
        && error.response.status
        && error.response.status === BAD_REQUEST_RESPONSE_STATUS
        && error.response.data
        && error.response.data.errors;
    },
    /**
     * Resets all errors and validates form again.
     *
     * @return {Promise<any>}
     */
    revalidateForm() {
      this.$validator.errors.clear();

      return this.$validator.validateAll();
    },
    /**
     * Validates single field.
     *
     * @param {string} fieldName Field name to validate
     */
    validateField(fieldName) {
      this.$validator.validate(fieldName);
    },
  },
};

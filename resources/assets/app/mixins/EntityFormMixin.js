/**
 * Mixin that contains common methods for forms that are used for creating or updating entities.
 */
import AlertsService from '../services/AlertsService';

export default {
  props: {
    value: {
      type:    Object,
      default: () => {
      },
    },
  },
  data() {
    return {
      item:    {},
      service: null,
    };
  },
  watch: {
    value(newValue) {
      this.item = Object.assign({}, newValue);
    },
  },
  methods: {
    /**
     * Performs save request.
     */
    async save() {
      if (!await this.revalidateForm()) {
        return;
      }

      this.service.save(this.item)
        .then(() => {
          AlertsService.info(this.$i18n.t('common.notifications.changesSaved'));
          this.$emit('saved');
          this.close();
        })
        .catch(error => {
          if (this.isValidationError(error)) {
            this.handleValidationError(error.response.data.errors);
          }
        });
    },
  },
};

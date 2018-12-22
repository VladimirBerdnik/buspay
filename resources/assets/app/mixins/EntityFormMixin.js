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
      item:       {},
      service:    null,
      inProgress: false,
      itemType:   null,
    };
  },
  watch: {
    value(newValue) {
      this.item = Object.assign({}, newValue);
    },
  },
  computed: {
    /**
     * Whether edited model exists or not.
     *
     * @return {boolean}
     */
    itemExists() {
      return Boolean(this.item.id);
    },
    /**
     * Returns whether form components editable or not.
     *
     * @return {boolean}
     */
    formEditable() {
      return this.itemExists
        ? this.policies.updatingAllowed(this.itemType)
        : this.policies.creationAllowed(this.itemType);
    },
  },
  mounted() {
    this.$on('close', () => { this.inProgress = false; });
  },
  methods: {
    /**
     * Performs save request.
     */
    async save() {
      if (this.inProgress) {
        return;
      }

      if (!await this.revalidateForm()) {
        return;
      }

      this.inProgress = true;

      this.service.save(this.item)
        .then(() => {
          this.inProgress = false;
          AlertsService.info(this.$i18n.t('common.notifications.changesSaved'));
          this.$emit('saved');
          this.close();
        })
        .catch(error => {
          this.inProgress = false;
          if (this.isValidationError(error)) {
            this.handleValidationError(error.response.data.errors);
          }
        });
    },
  },
};

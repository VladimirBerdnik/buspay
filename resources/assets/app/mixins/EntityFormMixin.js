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
      item:              {},
      service:           null,
      inProgress:        false,
      itemType:          null,
      partialIntentions: [],
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
        ? this.policies.canUpdate(this.itemType)
        : this.policies.canCreate(this.itemType);
    },
    /**
     * Determines whether form can be submitted or not.
     *
     * @return {boolean}
     */
    formSubmittable() {
      const partialIntentionsAllowed = Object.values(this.partialIntentions)
        .some(intention => this.policies.can(this.itemType, intention));

      if (partialIntentionsAllowed) {
        return true;
      }

      return this.formEditable;
    },
  },
  mounted() {
    this.$on('close', () => { this.inProgress = false; });
  },
  methods: {
    /**
     * Returns form item data.
     *
     * @return {Object}
     */
    getItemData() {
      return this.item;
    },
    /**
     * Performs save request.
     */
    async save() {
      if (!this.formSubmittable) {
        return;
      }

      if (this.inProgress) {
        return;
      }

      if (!await this.revalidateForm()) {
        return;
      }

      this.inProgress = true;

      this.service.save(this.getItemData())
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

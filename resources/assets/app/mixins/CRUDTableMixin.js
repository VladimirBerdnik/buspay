import AlertsService from '../services/AlertsService';
import UserInteractionService from '../services/UserInteractionService';
/**
 * Mixin for page with list of items. Has methods to open modal form of item and delete item feature.
 */
export default {
  data() {
    return {
      editModalVisible:     false,
      itemToEdit:           {},
      itemType:             null,
      itemStringIdentifier: null,
    };
  },
  methods: {
    /**
     * Opens modal window to create\edit item.
     *
     * @param {Object} itemToEdit Handled item to edit
     */
    openModalForm(itemToEdit) {
      this.itemToEdit = itemToEdit;
      this.editModalVisible = true;
    },
    /**
     * Closes item details modal window.
     */
    closeModalForm() {
      this.editModalVisible = false;
      this.itemToEdit = {};
    },
    /**
     * Deletes entity.
     *
     * @param {Object} item Item to delete
     */
    deleteItem(item) {
      UserInteractionService.handleConfirm({
        message: this.$i18n.t(`${this.itemType}.deleteConfirm`, { item: item[this.itemStringIdentifier || 'id'] }),
      })
        .then(() => {
          this.service.delete(item)
            .then(() => {
              AlertsService.info(this.$i18n.t('common.notifications.recordDeleted'));
              this.reloadTable();
            })
            .catch(() => {
            });
        })
        .catch(() => {
        });
    },
  },
};

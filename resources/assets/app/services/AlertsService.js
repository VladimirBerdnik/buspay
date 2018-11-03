import { mapMutations, mapGetters } from 'vuex';
import store from '../store/index';
import { ADD_ALERT_MUTATION, FORGOT_ALERT_MUTATION } from '../store/mutations';
import { ALERTS_GETTER } from '../store/getters';

const ALERT_TYPES = {
  SUCCESS: 'success',
  INFO:    'info',
  WARNING: 'warning',
  ERROR:   'error',
};

export default {
  $store: store,

  /**
   * Mutations from Vuex Store.
   */
  ...mapMutations({
    addAlert:    ADD_ALERT_MUTATION,
    forgotAlert: FORGOT_ALERT_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({ alerts: ALERTS_GETTER }),

  /**
   * Displays alert to user.
   *
   * @param {string} message Alert message to display
   * @param {string} type Alert type to display
   */
  showAlert(message, type) {
    this.addAlert({ message, type, ts: new Date() });
  },

  /**
   * Displays success message to user.
   *
   * @param {string} message Message to display
   */
  success(message) {
    this.addAlert({ message, type: ALERT_TYPES.SUCCESS });
  },

  /**
   * Displays warning message to user.
   *
   * @param {string} message Message to display
   */
  warning(message) {
    this.addAlert({ message, type: ALERT_TYPES.WARNING });
  },
  /**
   * Displays info message to user.
   *
   * @param {string} message Message to display
   */
  info(message) {
    this.addAlert({ message, type: ALERT_TYPES.INFO });
  },
  /**
   * Displays error message to user.
   *
   * @param {string} message Message to display
   */
  error(message) {
    this.addAlert({ message, type: ALERT_TYPES.ERROR });
  },

  /**
   * Returns list of alerts.
   *
   * @return {{message: string, type: string}[]} List of alerts
   */
  getAlerts() {
    return this.alerts();
  },

  /**
   * Removes alert from list to display.
   *
   * @param {{message: string, type: string}} alert Alert to remove
   */
  removeAlert(alert) {
    this.forgotAlert(alert);
  },
};

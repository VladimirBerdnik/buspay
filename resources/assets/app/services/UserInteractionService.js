import { mapMutations, mapGetters } from 'vuex';
import {
  OPEN_LOGIN_MODAL_MUTATION,
  CLOSE_LOGIN_MODAL_MUTATION,
  OPEN_CONFIRMATION_MODAL_MUTATION,
  CLOSE_CONFIRMATION_MODAL_MUTATION,
  OPEN_ERROR_NOTIFICATION_MODAL_MUTATION,
} from '../store/mutations';
import {
  CONFIRM_MODAL_GETTER,
  LOGIN_MODAL_GETTER,
} from '../store/getters';
import store from '../store/index';
import AuthService from './AuthService';

/**
 * Subscribe for window close mutation and handle closing result.
 *
 * @param {string} closeWindowMutationType Mutation type to subscribe
 * @param {function} resolve Handler to positive closing result
 * @param {function} reject Handler to negative close result
 */
function subscribeForWindowCloseResult(
  closeWindowMutationType,
  resolve,
  reject,
) {
  store.subscribe(mutation => {
    if (mutation.type === closeWindowMutationType) {
      if (mutation.payload) {
        resolve();
      }
      reject();
    }
  });
}

export default {
  $store: store,

  /**
   * Mutations from Vuex Storage
   */
  ...mapMutations({
    openLogin:         OPEN_LOGIN_MODAL_MUTATION,
    closeLogin:        CLOSE_LOGIN_MODAL_MUTATION,
    openConfirmation:  OPEN_CONFIRMATION_MODAL_MUTATION,
    closeConfirmation: CLOSE_CONFIRMATION_MODAL_MUTATION,
    openErrorModal:    OPEN_ERROR_NOTIFICATION_MODAL_MUTATION,
  }),

  /**
   * Getters from Vuex Storage.
   */
  ...mapGetters({
    confirmationDetails: CONFIRM_MODAL_GETTER,
    loginDetails:        LOGIN_MODAL_GETTER,
  }),

  /**
   * Open login dialog and wait login results.
   *
   * @return {Promise<any>}
   */
  handleLogin() {
    return new Promise((resolve, reject) => {
      subscribeForWindowCloseResult(
        CLOSE_LOGIN_MODAL_MUTATION,
        resolve,
        reject,
      );
      this.openLogin();
    });
  },

  /**
   * Open error notification modal.
   *
   * @param {{error: string}} params Data need to open this modal window
   * @return {Promise<any>}
   */
  openErrorNotificationModal(params) {
    this.openErrorModal(params);
  },

  /**
   * Performs passed action only for authorized user.
   *
   * @param resolve
   * @param reject
   */
  withAuth(resolve, reject) {
    if (AuthService.isAuthenticated()) {
      resolve();
    } else {
      this.handleLogin().then(resolve, reject);
    }
  },

  /**
   * Returns login window details.
   *
   * @return {{visible: boolean}} Window details
   */
  loginWindowParameters() {
    return this.loginDetails();
  },

  /**
   * Closes login modal with login result.
   *
   * @param {boolean} loginResult Login result
   */
  closeLoginModal(loginResult) {
    this.closeLogin(loginResult);
  },

  /**
   * Open confirm confirm dialog and wait results.
   *
   * @param {{title: string=, message: string}} params Data required to open this modal window
   * @return {Promise<any>}
   */
  handleConfirm(params) {
    return new Promise((resolve, reject) => {
      subscribeForWindowCloseResult(
        CLOSE_CONFIRMATION_MODAL_MUTATION,
        resolve,
        reject,
      );
      this.openConfirmation(params);
    });
  },

  /**
   * Returns confirmation window details.
   *
   * @return {{visible: boolean, params: {message: string, title: string}}} Window details
   */
  confirmationWindowParameters() {
    return this.confirmationDetails();
  },

  /**
   * Closes confirmation modal with confirmation result.
   *
   * @param {boolean} confirmed Confirmation result
   */
  closeConfirmationModal(confirmed) {
    this.closeConfirmation(confirmed);
  },
};

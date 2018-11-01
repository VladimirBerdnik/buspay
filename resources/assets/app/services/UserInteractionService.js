import { mapMutations } from 'vuex';
import {
  OPEN_LOGIN_MODAL_MUTATION,
  CLOSE_LOGIN_MODAL_MUTATION,
  OPEN_DELETE_CONFIRMATION_MODAL_MUTATION,
  CLOSE_DELETE_CONFIRMATION_MODAL_MUTATION,
  OPEN_ERROR_NOTIFICATION_MODAL_MUTATION,
} from '../store/mutations';
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
    openLogin:      OPEN_LOGIN_MODAL_MUTATION,
    openDelete:     OPEN_DELETE_CONFIRMATION_MODAL_MUTATION,
    openErrorModal: OPEN_ERROR_NOTIFICATION_MODAL_MUTATION,
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
   * Open confirm delete dialog and wait results.
   *
   * @param {{name: String}} params Data need to open this modal window. Ex : { name: 'Device 1' }
   * @return {Promise<any>}
   */
  handleConfirmDelete(params) {
    return new Promise((resolve, reject) => {
      subscribeForWindowCloseResult(
        CLOSE_DELETE_CONFIRMATION_MODAL_MUTATION,
        resolve,
        reject,
      );
      this.openDelete(params);
    });
  },

  /**
   * Open error notification modal.
   *
   * @param {{error: String}} params Data need to open this modal window
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
};

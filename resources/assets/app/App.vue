<template>
  <v-app id="inspire">

    <TheGlobalAlerts />

    <TheConfirmationModal :visible="confirmationDetails.visible"
                          :message="confirmationDetails.params.message"
                          :title="confirmationDetails.params.title"
                          @close="closeConfirmation"
    />

    <TheToolbar/>

    <v-content>
      <v-container fluid>
        <router-view/>
      </v-container>
    </v-content>

    <TheLoginModal :visible="loginModal.visible"
                   @close="closeLogin"
    />

    <TheFooter/>

  </v-app>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import { ERROR_NOTIFICATION_MODAL_GETTER } from './store/getters';
import { CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION } from './store/mutations';
import TheLoginModal from './components/TheLoginModal';
import TheConfirmationModal from './components/TheConfirmationModal';
import TheGlobalAlerts from './components/TheGlobalAlerts';
import TheToolbar from './components/TheToolbar';
import TheFooter from './components/TheFooter';
import UserInteractionService from './services/UserInteractionService';

export default {
  name:       'App',
  components: {
    TheToolbar,
    TheLoginModal,
    TheConfirmationModal,
    TheFooter,
    TheGlobalAlerts,
  },
  computed: {
    ...mapGetters({
      errorNotificationModal: ERROR_NOTIFICATION_MODAL_GETTER,
    }),
    /**
     * Login modal window details.
     *
     * @return {{visible: boolean}}
     */
    loginModal:          () => UserInteractionService.loginWindowParameters(),
    /**
     * Confirmation modal window details.
     *
     * @return {{visible: boolean, params: {message: string, title: string}}}
     */
    confirmationDetails: () => UserInteractionService.confirmationWindowParameters(),
  },
  methods: {
    ...mapMutations({
      closeErrorNotification: CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION,
    }),
    /**
     * Close login window with authentication result.
     *
     * @param {boolean} result Authenticated or not
     */
    closeLogin(result) {
      UserInteractionService.closeLoginModal(result);
    },
    /**
     * Close confirmation modal with confirmation result.
     *
     * @param {boolean} result Confirmation dialog result
     */
    closeConfirmation(result) {
      UserInteractionService.closeConfirmationModal(result);
    },
  },
};
</script>

<style>
  td.action-cell {
    cursor: pointer;
    color: #1976d2;
  }

  .cell-buttons {
    display: flex;
  }
</style>

<template>
  <v-app id="inspire">

    <TheToolbar/>

    <v-content>
      <v-container fluid>
        <router-view/>
      </v-container>
    </v-content>

    <TheLoginModal
      :visible="loginModal.visible"
      @close="closeLogin"
    />

    <TheFooter/>

  </v-app>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import TheLoginModal from './components/TheLoginModal';
import TheFooter from './components/TheFooter';
import {
  LOGIN_MODAL_GETTER,
  ERROR_NOTIFICATION_MODAL_GETTER,
} from './store/getters';
import {
  CLOSE_LOGIN_MODAL_MUTATION,
  CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION,
} from './store/mutations';
import TheToolbar from './components/TheToolbar';

export default {
  name:       'App',
  components: {
    TheToolbar,
    TheLoginModal,
    TheFooter,
  },
  computed: {
    ...mapGetters({
      loginModal:             LOGIN_MODAL_GETTER,
      errorNotificationModal: ERROR_NOTIFICATION_MODAL_GETTER,
    }),
  },
  methods: {
    ...mapMutations({
      closeLogin:             CLOSE_LOGIN_MODAL_MUTATION,
      closeErrorNotification: CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION,
    }),
  },
};
</script>

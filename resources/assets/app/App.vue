<template>
  <v-app id="inspire">
    <v-toolbar color="indigo"
               dark
               fixed
               app>
      <v-toolbar-items>
        <v-btn :to="{name: 'home'}"
               exact
               flat>{{ $t('app.title') }}</v-btn>
      </v-toolbar-items>
      <v-spacer/>
      <v-text-field
        :label="$t('layout.toolbar.menu.card.placeholder')"
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="credit_card"
        append-outer-icon="search"
        class="hidden-sm-and-down"
      />
      <v-spacer/>
      <v-toolbar-items>
        <LoginMenu v-if="!authenticated"/>
        <ProfileMenu v-if="authenticated"/>
      </v-toolbar-items>
    </v-toolbar>
    <v-content>
      <v-container fluid>
        <router-view/>
      </v-container>
      <LoginModal
        :visible="loginModal.visible"
        @close="closeLogin"
      />
    </v-content>
    <v-footer color="indigo"
              app/>
  </v-app>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import LoginModal from './components/LoginModal';
import LoginMenu from './components/LoginMenu';
import ProfileMenu from './components/ProfileMenu';
import AuthService from './services/AuthService';
import {
  LOGIN_MODAL_GETTER,
  ERROR_NOTIFICATION_MODAL_GETTER,
} from './store/getters';
import {
  CLOSE_LOGIN_MODAL_MUTATION,
  CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION,
} from './store/mutations';

export default {
  name:       'App',
  components: {
    LoginModal,
    LoginMenu,
    ProfileMenu,
  },
  props: {
    title: {
      type:    String,
      default: '',
    },
  },
  computed: {
    ...mapGetters({
      loginModal:             LOGIN_MODAL_GETTER,
      errorNotificationModal: ERROR_NOTIFICATION_MODAL_GETTER,
    }),
    authenticated: () => AuthService.isAuthenticated(),
  },
  methods: {
    ...mapMutations({
      closeLogin:             CLOSE_LOGIN_MODAL_MUTATION,
      closeErrorNotification: CLOSE_ERROR_NOTIFICATION_MODAL_MUTATION,
    }),
  },
};
</script>

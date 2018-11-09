<template>
  <v-toolbar :clipped-left="true"
             dark
             fixed
             app
  >
    <v-toolbar-items>
      <v-btn :to="{name: 'home'}"
             exact
             flat
      >
        <v-avatar :size="32"
                  class="mr-3"
        >
          <img src="/images/favicon.png">
        </v-avatar>
        {{ $t('app.title') }}
      </v-btn>
    </v-toolbar-items>
    <v-spacer/>
    <v-text-field
      :label="$t('layout.toolbar.menu.card.placeholder')"
      flat
      solo-inverted
      hide-details
      prepend-inner-icon="credit_card"
      append-outer-icon="search"
    />
    <v-spacer/>
    <v-toolbar-items>
      <TheLoginMenu v-if="!authenticated"
                    ref="loginMenu"
      />
      <TheProfileMenu v-else/>
    </v-toolbar-items>
  </v-toolbar>
</template>

<script>
import * as router from '../router';
import TheLoginMenu from './TheLoginMenu';
import TheProfileMenu from './TheProfileMenu';
import AuthService from '../services/AuthService';

export default {
  name:       'TheToolbar',
  components: {
    TheLoginMenu,
    TheProfileMenu,
  },
  computed: {
    authenticated: () => AuthService.isAuthenticated(),
  },
  watch: {
    authenticated(newValue, oldValue) {
      if ((newValue !== oldValue) && newValue) {
        this.goToCabinet();
      }
    },
  },
  methods: {
    /**
     * Navigates user to personal cabinet page.
     */
    goToCabinet() {
      this.$router.push({ name: router.ROUTE_CABINET });
    },
  },
};
</script>

<template>
  <v-toolbar :clipped-left="$vuetify.breakpoint.mdAndUp"
             dark
             fixed
             app
  >
    <v-toolbar-items>
      <v-btn :to="{name: 'home'}"
             exact
             flat>{{ $t('app.title') }}
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
      <TheLoginMenu v-if="!authenticated"/>
      <TheProfileMenu v-else/>
    </v-toolbar-items>
  </v-toolbar>
</template>

<script>
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
};
</script>

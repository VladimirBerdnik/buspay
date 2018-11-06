<template>
  <v-menu :nudge-bottom="0"
          offset-y
  >
    <v-btn slot="activator"
           flat
    >
      <span>{{ profileName }}</span>
      <v-icon dark>arrow_drop_down</v-icon>
    </v-btn>

    <v-list>
      <v-list-tile @click="goToCabinet">
        <v-list-tile-action>
          <v-icon>apps</v-icon>
        </v-list-tile-action>
        <v-list-tile-title>{{ $t('layout.toolbar.menu.cabinet') }}</v-list-tile-title>
      </v-list-tile>
      <v-list-tile @click="logout">
        <v-list-tile-action>
          <v-icon>exit_to_app</v-icon>
        </v-list-tile-action>
        <v-list-tile-title >{{ $t('layout.toolbar.menu.logout') }}</v-list-tile-title>
      </v-list-tile>
    </v-list>
  </v-menu>
</template>

<script>
import ProfileService from '../services/ProfileService';
import AuthService from '../services/AuthService';
import * as router from '../router';

export default {
  name: 'TheProfileMenu',
  data: () => ({

  }),
  computed: {
    /**
     * Returns authenticated user first name.
     *
     * @returns {string}
     */
    profileName() {
      const profile = ProfileService.get();

      if (!profile) {
        return '...';
      }

      return profile.first_name;
    },
  },
  mounted() {
    ProfileService.read();
  },
  methods: {
    /**
     * Navigates user to personal cabinet page.
     */
    goToCabinet() {
      this.$router.push({ name: router.ROUTE_CABINET });
    },
    /**
     * Performs user logout.
     */
    logout() {
      AuthService.logout();
      this.$router.push({ name: router.ROUTE_HOME });
    },
  },
};
</script>

<style scoped>

</style>

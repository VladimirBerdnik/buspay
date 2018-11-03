<template>
  <v-btn flat
         @click="performLogin"
  >
    {{ $t('layout.toolbar.menu.login') }}
  </v-btn>
</template>

<script>
import UserInteractionService from '../services/UserInteractionService';

export default {
  name:    'TheLoginMenu',
  methods: {
    /**
     * Initiates login process.
     */
    performLogin() {
      UserInteractionService.handleLogin();
    },
  },
};
</script>

<style scoped>

</style>

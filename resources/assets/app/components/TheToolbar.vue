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
    <v-flex>
      <v-tooltip bottom>
        <v-text-field
          slot="activator"
          :label="$t('layout.toolbar.menu.card.placeholder')"
          v-model="cardNumber"
          mask="########"
          flat
          solo-inverted
          hide-details
          prepend-inner-icon="credit_card"
          append-outer-icon="search"
          @click:append-outer="goToCardDetails"
          @change="goToCardDetails"
        />
        <span>{{ $t('pages.cardBalance.whatIsCardNumber') }}</span>
      </v-tooltip>
    </v-flex>
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
  data: () => ({
    cardNumber:               null,
    cardNumberTooltipVisible: false,
  }),
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
    /**
     * Navigates user to card details page.
     */
    goToCardDetails() {
      this.cardNumberTooltipVisible = false;

      if (!this.cardNumber) {
        return;
      }
      this.$router.push({
        name:   router.ROUTE_CARD_DETAILS,
        params: { cardNumber: this.cardNumber },
      });
    },
  },
};
</script>

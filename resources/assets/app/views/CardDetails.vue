<template>
  <v-layout>
    <v-flex xs12
            md6
            offset-md3
    >
      <v-card>
        <v-card-title primary-title
                      justify-center
        >
          <v-layout row
                    wrap
                    justify-between
          >
            <span class="headline mb-0">
              {{ $t('pages.cardBalance.cardNumberLabel') }} :
              <code class="grey--text text--darken-3">{{ cardNumber }}</code>
            </span>
            <v-spacer/>
            <span class="headline">
              {{ $t('pages.cardBalance.totalsLabel') }} :
              <span class="green--text">
                {{ totals === null ? '-' : totals }} {{ $t('app.currency') }}
              </span>
            </span>
          </v-layout>
        </v-card-title>
        <template v-if="recentOperations">
          <v-layout row
                    wrap
                    justify-center
          >
            <span class="headline">
              {{ $t('pages.cardBalance.recentDetails') }}
            </span>
          </v-layout>
          <v-timeline>
            <v-timeline-item
              v-for="(transaction, index) in recentOperations"
              :color="transaction.type === CardTransactionsTypes.replenishment ? 'green' : 'red'"
              :key="index"
              right
              small
            >
              <span slot="opposite">
                {{ transaction.date | timeStamp }}
              </span>
              <span :class="`headline font-weight-bold`">
                {{ transaction.amount }} {{ $t('app.currency') }}
              </span>
            </v-timeline-item>
          </v-timeline>
        </template>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import * as router from '../router';
import CardBalanceService from '../services/CardBalanceService';
import CardTransactionsTypes from '../enums/CardTransactionsTypes';

export default {
  name: 'CardDetails',
  data: () => ({
    years:            [],
    totals:           null,
    cardNumber:       null,
    recentOperations: null,
    CardTransactionsTypes,
  }),
  watch: {
    /**
     * Get card number from route when it is changed.
     *
     * @param {Object} to New route value
     */
    $route(to) {
      this.handleRoute(to);
    },
  },
  mounted() {
    this.handleRoute(this.$route);
  },
  methods: {
    /**
     * Reacts to route changing. WHen card number changed, for example.
     *
     * @param {Object} route Route to handle
     */
    handleRoute(route) {
      if (route.name !== router.ROUTE_CARD_DETAILS) {
        return;
      }
      this.setCardNumber(String(route.params.cardNumber).trim());
    },
    /**
     * Set card number that should be handled on the page.
     *
     * @param {Number|String} cardNumber New card number to handle on page
     */
    async setCardNumber(cardNumber) {
      this.cardNumber = cardNumber;
      this.totals = null;
      this.recentOperations = null;
      await this.loadCardTotals(this.cardNumber);
      await this.loadCardTransactions(this.cardNumber);
    },
    /**
     * Retrieves given card balance totals.
     *
     * @param {Number} cardNumber Card number for which need to retrieve card totals
     */
    async loadCardTotals(cardNumber) {
      this.totals = await CardBalanceService.totals(cardNumber);
    },
    /**
     * Retrieves given card transactions.
     *
     * @param {Number} cardNumber Card number for which need to retrieve card transactions
     */
    async loadCardTransactions(cardNumber) {
      this.recentOperations = await CardBalanceService.transactions(cardNumber);
    },
  },
};
</script>

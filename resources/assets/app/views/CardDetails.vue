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
              <span class="green--text">{{ totals === null ? '-' : totals }} ₸</span>
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
              v-for="(year, i) in years"
              :color="green"
              :key="i"
              right
              small
            >
              <span
                slot="opposite"
                :class="`headline font-weight-bold red--text`"
                v-text="year.year"
              />
              <span
                :class="`headline font-weight-bold green--text`"
                v-text="year.year"
              />
            </v-timeline-item>
          </v-timeline>
        </template>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import CardBalanceService from '../services/CardBalanceService';

export default {
  name: 'CardDetails',
  data: () => ({
    years:            [],
    totals:           null,
    cardNumber:       null,
    recentOperations: null,
  }),
  watch: {
    /**
     * Get card number from route when it is changed.
     *
     * @param {Object} to New route value
     */
    $route(to) {
      this.setCardNumber(to.params.cardNumber.trim());
    },
  },
  mounted() {
    this.setCardNumber(this.$route.params.cardNumber.trim());
  },
  methods: {
    /**
     * Set card number that should be handled on the page.
     *
     * @param {Number} cardNumber New card number to handle on page
     */
    setCardNumber(cardNumber) {
      this.cardNumber = cardNumber;
      this.getCardTotals(this.cardNumber);
    },
    /**
     * Retrieves given card balance totals.
     *
     * @param {Number} cardNumber Card number for which need to retrieve card totals
     */
    async getCardTotals(cardNumber) {
      this.totals = null;
      this.recentOperations = null;
      this.totals = await CardBalanceService.totals(cardNumber);
    },
  },
};
</script>

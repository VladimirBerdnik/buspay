/**
 * Mixin that manages card type identifier in component and synchronizes it with route query parameter.
 */
export default {
  data() {
    return {
      filters: {
        cardTypeId: null,
      },
    };
  },
  watch: {
    filters: {
      deep: true,
      handler(newValue, oldValue) {
        if (newValue.cardTypeId !== oldValue.cardTypeId) {
          this.$forceUpdate();
        }
      },
    },
    $route(to) {
      /**
       * When route changed new card type identifier parameter should be taken from route.
       */
      this.parseCardTypeFromRoute(to);
    },
  },
  mounted() {
    /**
     * When component mounted card type identifier parameter should be taken from route.
     */
    this.parseCardTypeFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses card type identifier parameter from route query.
     *
     * @param {*} route Route to retrieve card type identifier from
     */
    parseCardTypeFromRoute(route) {
      this.filters.cardTypeId = Number.parseInt(route.query.cardTypeId, 10) || null;
    },
    /**
     * Switches selected on component card type identifier.
     */
    switchCardType() {
      const query = Object.assign({}, this.$route.query);

      // Replace card type identifier parameter in current route query
      query.cardTypeId = this.filters.cardTypeId;
      if (!query.cardTypeId) {
        delete query.cardTypeId;
      }
      this.$router.push({ to: this.$route.name, query });
    },
  },
};

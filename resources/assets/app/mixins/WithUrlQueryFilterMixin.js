/**
 * Mixin that manages component filters and synchronizes it with route query parameters.
 */
export default {
  data() {
    return {
      filters: {},
    };
  },
  watch: {
    /**
     * WHen filter parameters changed need to gracefully re-render page.
     */
    filters: {
      deep: true,
      handler() {
        this.$forceUpdate();
      },
    },
    $route(to) {
      /**
       * When route changed new query parameters should be taken from route.
       */
      this.parseRouteParameters(to);
    },
  },
  mounted() {
    /**
     * When component mounted query parameters should be taken from route.
     */
    this.parseRouteParameters(this.$route);
  },
  methods: {
    /**
     * Parses query parameters from route
     *
     * @param {*} route Route to retrieve parameters from
     */
    parseRouteParameters(route) {
      Object.keys(this.filters).forEach(parameter => {
        this.filters[parameter] = Number.parseInt(route.query[parameter], 10) || null;
      });
    },
    /**
     * Synchronizes URL query parameters with component filters.
     */
    updateQueryParameters() {
      const query = Object.assign({}, this.$route.query);

      Object.keys(this.filters).forEach(parameter => {
        query[parameter] = this.filters[parameter];
        if (!query[parameter]) {
          delete query[parameter];
        }
      });

      this.$router.push({ to: this.$route.name, query });
    },
  },
};

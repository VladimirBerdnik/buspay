/**
 * Mixin that manages bus route identifier in component and synchronizes it with route query parameter.
 */
export default {
  data() {
    return {
      filters: {
        routeId: null,
      },
    };
  },
  watch: {
    filters: {
      deep: true,
      handler(newValue, oldValue) {
        if (newValue.routeId !== oldValue.routeId) {
          this.$forceUpdate();
        }
      },
    },
    $route(to) {
      /**
       * When route changed new bus route identifier parameter should be taken from route.
       */
      this.parseRouteFromRoute(to);
    },
  },
  mounted() {
    /**
     * When component mounted bus route identifier parameter should be taken from route.
     */
    this.parseRouteFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses bus route identifier parameter from route query.
     *
     * @param {*} route Route to retrieve bus route identifier from
     */
    parseRouteFromRoute(route) {
      this.filters.routeId = Number.parseInt(route.query.routeId, 10) || null;
    },
    /**
     * Switches selected on component bus route identifier.
     */
    switchRoute() {
      const query = Object.assign({}, this.$route.query);

      // Replace bus route identifier parameter in current route query
      query.routeId = this.filters.routeId;
      if (!query.routeId) {
        delete query.routeId;
      }
      this.$router.push({ to: this.$route.name, query });
    },
  },
};

/**
 * Mixin that manages bus identifier in component and synchronizes it with route query parameter.
 */
export default {
  data() {
    return {
      filters: {
        busId: null,
      },
    };
  },
  watch: {
    filters: {
      deep: true,
      handler(newValue, oldValue) {
        if (newValue.busId !== oldValue.busId) {
          this.$forceUpdate();
        }
      },
    },
    $route(to) {
      /**
       * When route changed new bus identifier parameter should be taken from route.
       */
      this.parseBusFromRoute(to);
    },
  },
  mounted() {
    /**
     * When component mounted bus identifier parameter should be taken from route.
     */
    this.parseBusFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses bus identifier parameter from route query.
     *
     * @param {*} route Route to retrieve bus identifier from
     */
    parseBusFromRoute(route) {
      this.filters.busId = Number.parseInt(route.query.busId, 10) || null;
    },
    /**
     * Switches selected on component bus identifier.
     */
    switchBus() {
      const query = Object.assign({}, this.$route.query);

      // Replace bus identifier parameter in current route query
      query.busId = this.filters.busId;
      if (!query.busId) {
        delete query.busId;
      }
      this.$router.push({ to: this.$route.name, query });
    },
  },
};

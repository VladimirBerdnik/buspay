/**
 * Mixin that manages bus identifier in component and synchronizes it with route query parameter.
 */
export default {
  data() {
    return {
      busId: null,
    };
  },
  watch: {
    busId() {
      this.$forceUpdate();
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
      this.busId = Number.parseInt(route.query.busId, 10) || null;
    },
    /**
     * Switches selected on component bus identifier.
     */
    switchBus() {
      const query = Object.assign({}, this.$route.query);

      // Replace bus identifier parameter in current route query
      query.busId = this.busId;
      if (!query.busId) {
        delete query.busId;
      }
      this.$router.push({ to: this.$route.name, query });
    },
  },
};

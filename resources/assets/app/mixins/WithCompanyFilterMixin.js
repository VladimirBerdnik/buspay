/**
 * Mixin that manages company identifier in component and synchronizes it with route query parameter.
 */
export default {
  data() {
    return {
      companyId: null,
    };
  },
  watch: {
    companyId() {
      this.$forceUpdate();
    },
    $route(to) {
      /**
       * When route changed new company identifier parameter should be taken from route.
       */
      this.parseCompanyFromRoute(to);
    },
  },
  mounted() {
    /**
     * When component mounted company identifier parameter should be taken from route.
     */
    this.parseCompanyFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses company identifier parameter from route query.
     *
     * @param {*} route Route to retrieve company identifier from
     */
    parseCompanyFromRoute(route) {
      this.companyId = Number.parseInt(route.query.companyId, 10) || null;
    },
    /**
     * Switches selected on component company identifier.
     */
    switchCompany() {
      const query = Object.assign({}, this.$route.query);

      // Replace company identifier parameter in current route query
      query.companyId = this.companyId || null;
      this.$router.push({ to: this.$route.name, query });
    },
  },
};

<template>
  <v-select :items="routes"
            v-model="routeId"
            :label="$t('route.name')"
            :error-messages="errorMessages"
            :clearable="clearable"
            :no-data-text="$t('dropdowns.noResults')"
            item-text="name"
            item-value="id"
  />
</template>

<script>
import RoutesService from '../../services/RoutesService';

export default {
  name:  'RouteSelect',
  props: {
    withCompaniesOnly: {
      type:    Boolean,
      default: true,
    },
    companyId: {
      type:    Number,
      default: null,
    },
    value: {
      type:    Number,
      default: null,
    },
    errorMessages: {
      type:    Array,
      default: () => [],
    },
    clearable: {
      type:    Boolean,
      default: true,
    },
  },
  data() {
    return {
      routeId: null,
    };
  },
  computed: {
    /**
     * Returns only appropriate routes.
     *
     * @return {Route[]} List of routes that matches given criteria
     */
    routes() {
      return RoutesService.get().filter(route => {
        const companyMatch = (!this.companyId || this.companyId === route.company_id);

        return (!this.withCompaniesOnly || route.company_id) &&  companyMatch;
      });
    },
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    routeId(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * No any value should be selected when list of routes is empty.
     *
     * @param {Route[]} newRoutes New filtered list of routes
     */
    routes(newRoutes) {
      if (!this.valueValid(this.routeId, newRoutes)) {
        this.changeValue(null);
      }
    },
  },
  methods: {
    /**
     * Changes component value and notifies parent about value changing.
     *
     * @param {number} newValue New component selected value
     */
    changeValue(newValue) {
      this.routeId = newValue;
      this.$emit('input', newValue);
    },
    /**
     * Check whether passed value is valid value to select.
     *
     * @param {number} value Value to check
     * @param {Route[]|null} allowedValues List of allowed routes to check in. When not passed
     * component routes will be taken
     *
     * @return {boolean}
     */
    valueValid(value, allowedValues = null) {
      return (allowedValues || this.routes).some(route => route.id === value);
    },
  },
};
</script>

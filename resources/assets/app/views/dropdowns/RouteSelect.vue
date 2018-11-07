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
    routes: () => RoutesService.get(),
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.routeId = newValue;
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    routeId(newValue) {
      this.$emit('input', newValue);
    },
  },
};
</script>

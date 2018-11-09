<template>
  <v-select v-model="itemId"
            :items="items"
            :label="$t('route.name')"
            :clearable="clearable"
            :readonly="readonly"
            :error-messages="errorMessages"
            :no-data-text="$t('dropdowns.noResults')"
            :item-text="itemText"
            :item-value="itemValue"
  />
</template>

<script>
import RoutesService from '../../services/RoutesService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'RouteSelect',
  mixins: [SimpleDropdownMixin],
  props:  {
    withCompaniesOnly: {
      type:    Boolean,
      default: true,
    },
    companyId: {
      type:    Number,
      default: null,
    },
  },
  data() {
    return {
      service: RoutesService,
    };
  },
  computed: {
    /**
     * Returns only appropriate routes.
     *
     * @return {Route[]} List of routes that matches given criteria
     */
    items() {
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
    itemId(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * No any value should be selected when list of routes is empty.
     *
     * @param {Route[]} newItems New filtered list of routes
     */
    items(newItems) {
      if (!this.valueValid(this.itemId, newItems)) {
        this.changeValue(null);
      }
    },
  },
  methods: {
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
      return (allowedValues || this.items).some(item => item[this.itemValue] === value);
    },
  },
};
</script>

<template>
  <v-autocomplete v-model="busId"
                  :items="buses"
                  :readonly="false"
                  :label="$t('bus.name')"
                  :clearable="clearable"
                  :error-messages="errorMessages"
                  :no-data-text="$t('dropdowns.noResults')"
                  item-text="state_number"
                  item-value="id"
                  persistent-hint
  />
</template>

<script>
import BusesService from '../../services/BusesService';

export default {
  name:  'BusSelect',
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
      busId: null,
    };
  },
  computed: {
    /**
     * Returns only appropriate buses.
     *
     * @return {Bus[]} List of buses that matches given criteria
     */
    buses() {
      return BusesService.get().filter(bus => {
        const companyMatch = (!this.companyId || this.companyId === bus.company_id);

        return (!this.withCompaniesOnly || bus.company_id) &&  companyMatch;
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
    busId(newValue) {
      this.changeValue(this.valueValid(newValue) ? newValue : null);
    },
    /**
     * No any value should be selected when list of buses is empty.
     *
     * @param {Bus[]} newBuses New filtered list of buses
     */
    buses(newBuses) {
      if (!this.valueValid(this.busId, newBuses)) {
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
      this.busId = newValue;
      this.$emit('input', newValue);
    },
    /**
     * Check whether passed value is valid value to select.
     *
     * @param {number} value Value to check
     * @param {Bus[]|null} allowedValues List of allowed buses to check in. When not passed
     * component buses will be taken
     *
     * @return {boolean}
     */
    valueValid(value, allowedValues = null) {
      return (allowedValues || this.buses).some(bus => bus.id === value);
    },
  },
};
</script>

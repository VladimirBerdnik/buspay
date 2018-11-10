<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('bus.name')"
                  :clearable="clearable"
                  :readonly="readonly"
                  :error-messages="errorMessages"
                  :item-text="itemText"
                  :item-value="itemValue"
                  persistent-hint
  />
</template>

<script>
import BusesService from '../../services/BusesService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'BusSelect',
  mixins: [SimpleDropdownMixin],
  props:  {
    companyId: {
      type:    Number,
      default: null,
    },
  },
  data() {
    return {
      itemText: 'state_number',
      service:  BusesService,
    };
  },
  computed: {
    /**
     * Returns only appropriate buses.
     *
     * @return {Bus[]} List of buses that matches given criteria
     */
    items() {
      return BusesService.get()
        .filter(bus => (!this.companyId || this.companyId === bus.company_id));
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
     * No any value should be selected when list of buses is empty.
     *
     * @param {Bus[]} newItems New filtered list of buses
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
     * @param {Bus[]|null} allowedValues List of allowed buses to check in. When not passed
     * component buses will be taken
     *
     * @return {boolean}
     */
    valueValid(value, allowedValues = null) {
      return (allowedValues || this.items).some(item => item[this.itemValue] === value);
    },
  },
};
</script>

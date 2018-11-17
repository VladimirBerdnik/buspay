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
import FilterableDropdownMixin from '../../mixins/FilterableDropdownMixin';

export default {
  name:   'BusSelect',
  mixins: [FilterableDropdownMixin],
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
};
</script>

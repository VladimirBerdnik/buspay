<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('driver.name')"
                  :clearable="clearable"
                  :readonly="readonly"
                  :error-messages="errorMessages"
                  :item-text="itemText"
                  :item-value="itemValue"
                  persistent-hint
  />
</template>

<script>
import DriversService from '../../services/DriversService';
import FilterableDropdownMixin from '../../mixins/FilterableDropdownMixin';

export default {
  name:   'DriverSelect',
  mixins: [FilterableDropdownMixin],
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
      itemText: 'full_name',
      service:  DriversService,
    };
  },
  computed: {
    /**
     * Returns only appropriate drivers.
     *
     * @return {Driver[]} List of drivers that matches given criteria
     */
    items() {
      return DriversService.get().filter(driver => {
        const companyMatch = (!this.companyId || this.companyId === driver.company_id);

        return (!this.withCompaniesOnly || driver.company_id) && companyMatch;
      });
    },
  },
};
</script>

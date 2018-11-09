<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('company.name')"
                  :clearable="clearable"
                  :readonly="readonly"
                  :error-messages="errorMessages"
                  :no-data-text="$t('dropdowns.noResults')"
                  :item-text="itemText"
                  :item-value="itemValue"
                  persistent-hint
  />
</template>

<script>
import CompaniesService from '../../services/CompaniesService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'CompanySelect',
  mixins: [SimpleDropdownMixin],
  data() {
    return {
      service: CompaniesService,
    };
  },
};
</script>

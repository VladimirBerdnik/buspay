<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('company.name')"
                  :clearable="clearable"
                  :readonly="readonly"
                  :error-messages="errorMessages"
                  :item-text="itemText"
                  :item-value="itemValue"
                  :loading="loading"
                  persistent-hint
                  append-outer-icon="cached"
                  @click:append-outer="reloadItems"
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

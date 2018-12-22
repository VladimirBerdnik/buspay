<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('company.name')"
                  :clearable="!readonly && clearable"
                  :readonly="readonly"
                  :error-messages="errorMessages"
                  :item-text="itemText"
                  :item-value="itemValue"
                  :loading="loading"
                  :append-outer-icon="!readonly ? 'cached' : null"
                  persistent-hint
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

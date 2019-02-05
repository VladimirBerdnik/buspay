<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('tariff.name')"
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
import TariffsService from '../../services/TariffsService';
import FilterableDropdownMixin from '../../mixins/FilterableDropdownMixin';

export default {
  name:   'TariffSelect',
  mixins: [FilterableDropdownMixin],
  data() {
    return {
      itemText: 'serial_number',
      service:  TariffsService,
    };
  },
};
</script>

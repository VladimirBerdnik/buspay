<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('validator.name')"
                  :clearable="!readonly && clearable"
                  :readonly="readonly"
                  :disabled="disabled"
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
import ValidatorsService from '../../services/ValidatorsService';
import FilterableDropdownMixin from '../../mixins/FilterableDropdownMixin';

export default {
  name:   'ValidatorSelect',
  mixins: [FilterableDropdownMixin],
  data() {
    return {
      itemText: 'serial_number',
      service:  ValidatorsService,
    };
  },
};
</script>

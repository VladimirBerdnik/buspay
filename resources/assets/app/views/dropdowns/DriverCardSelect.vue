<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('card.name')"
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
import DriversCardsService from '../../services/DriversCardsService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'DriverCardSelect',
  mixins: [SimpleDropdownMixin],
  data() {
    return {
      itemText: 'card_number',
      service:  DriversCardsService,
    };
  },
};
</script>

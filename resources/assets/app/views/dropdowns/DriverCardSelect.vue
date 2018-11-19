<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('card.name')"
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

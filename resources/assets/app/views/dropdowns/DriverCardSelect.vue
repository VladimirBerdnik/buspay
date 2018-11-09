<template>
  <v-autocomplete v-model="itemId"
                  :items="items"
                  :label="$t('card.name')"
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

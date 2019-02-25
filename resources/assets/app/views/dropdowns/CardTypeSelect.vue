<template>
  <v-select v-model="itemId"
            :items="items"
            :label="$t('cardType.name')"
            :clearable="!readonly && clearable"
            :readonly="readonly"
            :disabled="disabled"
            :error-messages="errorMessages"
            :item-text="itemText"
            :item-value="itemValue"
  />
</template>

<script>
import CardTypesService from '../../services/CardTypesService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'CardTypesSelect',
  mixins: [SimpleDropdownMixin],
  data() {
    return {
      service: CardTypesService,
    };
  },
};
</script>

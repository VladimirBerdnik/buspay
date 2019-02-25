<template>
  <v-select v-model="itemId"
            :items="items"
            :label="$t('route.name')"
            :clearable="!readonly && clearable"
            :readonly="readonly"
            :disabled="disabled"
            :error-messages="errorMessages"
            :item-text="itemText"
            :item-value="itemValue"
            :append-outer-icon="!readonly ? 'cached' : null"
            :loading="loading"
            @click:append-outer="reloadItems"
  />
</template>

<script>
import RoutesService from '../../services/RoutesService';
import FilterableDropdownMixin from '../../mixins/FilterableDropdownMixin';

export default {
  name:   'RouteSelect',
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
      service: RoutesService,
    };
  },
  computed: {
    /**
     * Returns only appropriate routes.
     *
     * @return {Route[]} List of routes that matches given criteria
     */
    items() {
      return RoutesService.get().filter(route => {
        const companyMatch = (!this.companyId || this.companyId === route.company_id);

        return (!this.withCompaniesOnly || route.company_id) && companyMatch;
      });
    },
  },
};
</script>

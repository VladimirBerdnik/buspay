<template>
  <v-autocomplete
    v-model="companyId"
    :items="companies"
    :readonly="false"
    :label="$t('company.name')"
    :clearable="clearable"
    :error-messages="errorMessages"
    :no-data-text="$t('dropdowns.noResults')"
    item-text="name"
    item-value="id"
    persistent-hint
    prepend-icon="business"
  />
</template>

<script>
import CompaniesService from '../../services/CompaniesService';

export default {
  name:  'CompanySelect',
  props: {
    value: {
      type:    Number,
      default: null,
    },
    errorMessages: {
      type:    Array,
      default: () => [],
    },
    clearable: {
      type:    Boolean,
      default: false,
    },
  },
  data() {
    return {
      companyId: null,
    };
  },
  computed: {
    companies: () => CompaniesService.getCompanies(),
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.companyId = newValue;
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    companyId(newValue) {
      this.$emit('input', newValue);
    },
  },
};
</script>

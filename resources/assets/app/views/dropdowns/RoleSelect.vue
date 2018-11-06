<template>
  <v-select :items="roles"
            v-model="roleId"
            :label="$t('role.name')"
            :error-messages="errorMessages"
            :clearable="clearable"
            :no-data-text="$t('dropdowns.noResults')"
            item-text="name"
            item-value="id"
  />
</template>

<script>
import RolesService from '../../services/RolesService';

export default {
  name:  'RoleSelect',
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
      roleId: null,
    };
  },
  computed: {
    roles: () => RolesService.get(),
  },
  watch: {
    /**
     * Update value in component when new model value is set from parent.
     *
     * @param {number} newValue New value from parent component
     */
    value(newValue) {
      this.roleId = newValue;
    },
    /**
     * Fire event to parent when new value is selected in component.
     *
     * @param {number} newValue Selected value
     */
    roleId(newValue) {
      this.$emit('input', newValue);
    },
  },
};
</script>

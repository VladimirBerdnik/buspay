<template>
  <v-select v-model="itemId"
            :items="items"
            :label="$t('role.name')"
            :clearable="!readonly && clearable"
            :readonly="readonly"
            :disabled="disabled"
            :error-messages="errorMessages"
            :item-text="itemText"
            :item-value="itemValue"
  />
</template>

<script>
import RolesService from '../../services/RolesService';
import SimpleDropdownMixin from '../../mixins/SimpleDropdownMixin';

export default {
  name:   'RoleSelect',
  mixins: [SimpleDropdownMixin],
  data() {
    return {
      service: RolesService,
    };
  },
};
</script>

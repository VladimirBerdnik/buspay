<template>
  <v-layout row
            wrap
  >
    <v-flex class="mb-3"
            xs12
    >
      <v-layout row
                wrap
      >
        <v-text-field
          v-model="search"
          :label="$t('common.placeholders.search')"
          append-icon="search"
          hide-details
          single-line
          clearable
        />
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :rows-per-page-items="datatablesConfig.paginatorValues"
                    :items="items"
                    :search="search"
                    :loading="loadingInProgress"
                    item-key="id"
                    class="elevation-1"
      >
        <template slot="headerCell"
                  slot-scope="props"
        >
          <v-btn v-if="props.header.actionsColumn"
                 :title="$t('common.buttons.refresh')"
                 flat
                 icon
                 @click="reloadTable"
          >
            <v-icon>cached</v-icon>
          </v-btn>
          <template v-else>
            {{ props.header.text }}
          </template>
        </template>

        <v-progress-linear slot="progress"
                           color="blue"
                           indeterminate
        />

        <template
          slot="items"
          slot-scope="props"
        >
          <td>{{ props.item.id }}</td>
          <td class="action-cell"
              @click.stop="openModalForm(props.item)"
          >
            {{ props.item.serial_number }}
          </td>
          <td>{{ props.item.model }}</td>
          <td>{{ props.item.bus.state_number }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn v-show="policies.canUpdate(policies.itemsTypes.validators)"
                     flat
                     icon
                     class="mx-0"
                     @click.stop="openModalForm(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
            </div>
          </td>
        </template>

      </v-data-table>

      <ValidatorForm :visible="editModalVisible"
                     :value="itemToEdit"
                     @close="closeModalForm"
                     @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import ValidatorsService from '../../services/ValidatorsService';
import ValidatorForm from '../../views/forms/ValidatorForm';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import PoliciesService from '../../services/PoliciesService';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'serial_number' },
  { value: 'model' },
  { value: 'bus.state_number' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`validator.fields.${header.value}`);
});

// Actions column
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Validators',
  components: {
    ValidatorForm,
  },
  mixins: [ CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      search:               null,
      service:              ValidatorsService,
      itemType:             PoliciesService.itemsTypes.validators,
      itemStringIdentifier: 'serial_number',
    };
  },
};
</script>

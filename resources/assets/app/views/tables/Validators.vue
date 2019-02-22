<template>
  <div>
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
          class="pr-3 flex xs6 sm4 md3"
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
          <ActionCell :item-type="itemType"
                      :intention="policies.intentions.show"
                      @activate="openModalForm(props.item)"
          >
            {{ props.item.serial_number }}
          </ActionCell>
          <td>{{ props.item.model }}</td>
          <td>{{ props.item.external_id }}</td>
          <td>{{ props.item.bus.state_number }}</td>
          <td>{{ props.item.bus.company ? props.item.bus.company.name : '' }}</td>
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
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import ValidatorsService from '../../services/ValidatorsService';
import ValidatorForm from '../../views/forms/ValidatorForm';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'serial_number' },
  { value: 'model' },
  { value: 'external_id' },
  { value: 'bus.state_number' },
  { value: 'bus.company.name' },
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
    ActionCell,
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

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
        <CompanySelect v-if="policies.canSeeList(policies.itemsTypes.companies)"
                       v-model="filters.companyId"
                       class="pr-3 flex xs6 sm4 md3"
                       @input="updateQueryParameters"
        />
        <BusSelect v-if="policies.canSeeList(policies.itemsTypes.buses)"
                   v-model="filters.busId"
                   :company-id="filters.companyId"
                   class="pr-3 flex xs6 sm4 md3"
                   @input="updateQueryParameters"
        />
      </v-layout>
      <v-layout v-show="policies.canCreate(itemType)">
        <v-spacer/>
        <v-btn color="primary"
               @click="openModalForm({company_id: filters.companyId, bus_id: filters.busId})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
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
            {{ props.item.full_name }}
          </ActionCell>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.bus.state_number }}</td>
          <td>{{ props.item.card.card_number }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn v-show="policies.canUpdate(itemType)
                     || policies.can(itemType, policies.intentions.changeDriverBus)"
                     flat
                     icon
                     class="mx-0"
                     @click.stop="openModalForm(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn v-show="policies.canDelete(itemType)"
                     flat
                     icon
                     class="mx-0"
                     @click.stop="deleteItem(props.item)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>

      </v-data-table>

      <DriverForm :visible="editModalVisible"
                  :value="itemToEdit"
                  @close="closeModalForm"
                  @saved="reloadTable"
      />
    </v-flex>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import DriversService from '../../services/DriversService';
import DriverForm from '../../views/forms/DriverForm';
import CompanySelect from '../dropdowns/CompanySelect';
import BusSelect from '../dropdowns/BusSelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'full_name' },
  { value: 'company.name' },
  { value: 'bus.state_number' },
  { value: 'card.card_number' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`driver.fields.${header.value}`);
});

// Actions column
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Drivers',
  components: {
    ActionCell,
    CompanySelect,
    BusSelect,
    DriverForm,
  },
  mixins: [ WithUrlQueryFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      service:              DriversService,
      itemType:             PoliciesService.itemsTypes.drivers,
      itemStringIdentifier: 'full_name',
      search:               null,
      filters:              {
        companyId: null,
        busId:     null,
      },
    };
  },
};
</script>

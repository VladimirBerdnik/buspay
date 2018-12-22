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
          class="mr-3"
        />
        <CompanySelect v-model="filters.companyId"
                       class="mr-3"
                       @input="updateQueryParameters"
        />
        <RouteSelect v-model="filters.routeId"
                     :company-id="filters.companyId"
                     @input="updateQueryParameters"
        />
        <v-btn v-show="policies.canCreate(itemType)"
               color="primary"
               @click="openModalForm({company_id: filters.companyId, route_id: filters.routeId})"
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
        <v-progress-linear slot="progress"
                           color="blue"
                           indeterminate
        />

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

        <template
          slot="items"
          slot-scope="props"
        >
          <td>{{ props.item.id }}</td>
          <ActionCell :item-type="itemType"
                      :intention="policies.intentions.show"
                      @activate="openModalForm(props.item)"

          >
            {{ props.item.state_number }}
          </ActionCell>
          <td>{{ props.item.model_name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.route.name }}</td>
          <ActionCell :item-type="policies.itemsTypes.drivers"
                      :intention="policies.intentions.get"
                      class="text-xs-right"
                      @activate="goToDrivers(props.item.id)"

          >
            {{ props.item.drivers_count }}
          </ActionCell>
          <td class="text-xs-right">{{ props.item.validators_count }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn v-show="policies.canUpdate(itemType)
                     || policies.can(itemType, policies.intentions.changeBusRoute)"
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

      <BusForm :visible="editModalVisible"
               :value="itemToEdit"
               @close="closeModalForm"
               @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import * as routes from '../../router';
import BusesService from '../../services/BusesService';
import CompanySelect from '../dropdowns/CompanySelect';
import BusForm from '../forms/BusForm';
import RouteSelect from '../dropdowns/RouteSelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'state_number' },
  { value: 'model_name' },
  { value: 'company.name' },
  { value: 'route.name' },
  { value: 'drivers_count' },
  { value: 'validators_count' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`bus.fields.${header.value}`);
});

// Actions column
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Buses',
  components: {
    ActionCell,
    RouteSelect,
    CompanySelect,
    BusForm,
  },
  mixins: [ WithUrlQueryFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      search:  null,
      filters: {
        companyId: null,
        routeId:   null,
      },
      service:              BusesService,
      itemType:             PoliciesService.itemsTypes.buses,
      itemStringIdentifier: 'state_number',
    };
  },
  methods: {
    /**
     * Navigates user to drivers list page.
     *
     * @param {number} busId Bus identifier to display drivers list for
     */
    goToDrivers(busId) {
      this.$router.push({ name: routes.ROUTE_DRIVERS, query: { busId } });
    },
  },
};
</script>

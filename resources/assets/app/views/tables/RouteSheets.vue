<template>
  <div>
    <v-flex class="mb-3"
            xs12
    >
      <v-layout row
                wrap
      >
        <CompanySelect v-model="filters.companyId"
                       class="mr-3"
                       @input="updateQueryParameters"
        />
        <RouteSelect v-model="filters.routeId"
                     :company-id="filters.companyId"
                     class="mr-3"
                     @input="updateQueryParameters"
        />
        <BusSelect v-model="filters.busId"
                   :company-id="filters.companyId"
                   class="mr-3"
                   @input="updateQueryParameters"
        />
        <DriverSelect v-model="filters.driverId"
                      :company-id="filters.companyId"
                      class="mr-3"
                      @input="updateQueryParameters"
        />
        <DateSelect v-model="activeFrom"
                    :label="$t('routeSheet.fields.active_from')"
                    :default-hours="0"
                    :default-minutes="0"
                    :default-seconds="0"
                    time-as-hint
                    class="mr-3 v-input"
        />
        <DateSelect v-model="activeTo"
                    :label="$t('routeSheet.fields.active_to')"
                    :default-hours="23"
                    :default-minutes="59"
                    :default-seconds="59"
                    time-as-hint
                    class="v-input"
        />
      </v-layout>
      <v-layout row>
        <v-spacer/>
        <v-btn v-if="policies.canCreate(itemType)"
               color="primary"
               @click="openModalForm({
                 company_id: filters.companyId,
                 route_id: filters.routeId,
                 bus_id: filters.busId,
                 driver_id: filters.driverId
               })"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-data-table :headers="headers"
                  :rows-per-page-items="datatablesConfig.serverSidePaginatorValues"
                  :pagination.sync="pagination"
                  :total-items="totalItems"
                  :custom-sort="items => items"
                  :custom-filter="items => items"
                  :items="items"
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

      <template slot="items"
                slot-scope="props"
      >
        <ActionCell :item-type="itemType"
                    :intention="policies.intentions.show"
                    @activate="openModalForm(props.item)"

        >
          {{ props.item.id }}
        </ActionCell>
        <td>{{ props.item.company.name }}</td>
        <td>{{ props.item.route.name }}</td>
        <td>{{ props.item.bus.state_number }}</td>
        <td>{{ props.item.driver.full_name }}</td>
        <td>{{ props.item.active_from | fullTimeStamp }}</td>
        <td>{{ props.item.active_to | fullTimeStamp }}</td>
        <td class="px-0">
          <div class="cell-buttons">
            <v-btn v-show="policies.canUpdate(policies.itemsTypes.routeSheets)"
                   flat
                   icon
                   class="mx-0"
                   @click.stop="openModalForm(props.item)"
            >
              <v-icon>edit</v-icon>
            </v-btn>
            <v-btn v-show="policies.canDelete(policies.itemsTypes.routeSheets)"
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

    <div class="text-xs-center pt-2">
      <v-pagination v-model="pagination.page"
                    :length="totalPages"
                    :total-visible="datatablesConfig.paginatorLength"
                    dark
      />
    </div>

    <RouteSheetForm :visible="editModalVisible"
                    :value="itemToEdit"
                    @close="closeModalForm"
                    @saved="reloadTable"
    />
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import RouteSheetsService from '../../services/RouteSheetsService';
import PaginatedTableMixin from '../../mixins/PaginatedTableMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RouteSelect from '../dropdowns/RouteSelect';
import BusSelect from '../dropdowns/BusSelect';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import DriverSelect from '../dropdowns/DriverSelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import RouteSheetForm from '../forms/RouteSheetForm';
import DateSelect from '../dropdowns/DateSelect';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'company.name' },
  { value: 'route.name' },
  { value: 'bus.state_number' },
  { value: 'driver.full_name' },
  { value: 'active_from' },
  { value: 'active_to' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`routeSheet.fields.${header.value}`);
});

headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'RouteSheets',
  components: {
    ActionCell,
    DateSelect,
    RouteSheetForm,
    DriverSelect,
    CompanySelect,
    BusSelect,
    RouteSelect,
  },
  mixins: [ PaginatedTableMixin, WithUrlQueryFilterMixin, CRUDTableMixin ],
  data() {
    return {
      headers,
      service: RouteSheetsService,
      search:  null,
      filters: {
        companyId: null,
        routeId:   null,
        driverId:  null,
        busId:     null,
      },
      itemType:             PoliciesService.itemsTypes.routeSheets,
      itemStringIdentifier: 'id',
    };
  },
};
</script>

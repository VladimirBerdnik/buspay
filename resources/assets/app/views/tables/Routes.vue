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
      </v-layout>
      <v-layout v-show="policies.canCreate(itemType)">
        <v-spacer/>
        <v-btn v-show="policies.canCreate(itemType)"
               color="primary"
               @click="openModalForm({company_id: filters.companyId})"
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
                    hide-actions
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
            {{ props.item.name }}
          </ActionCell>
          <td>{{ props.item.company.name }}</td>
          <ActionCell :item-type="policies.itemsTypes.buses"
                      :intention="policies.intentions.get"
                      @activate="goToBuses(props.item.id)"

          >
            {{ props.item.buses_count }}
          </ActionCell>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn v-show="policies.canUpdate(itemType)"
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

      <RouteForm :visible="editModalVisible"
                 :value="itemToEdit"
                 @close="closeModalForm"
                 @saved="reloadTable"
      />
    </v-flex>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import RoutesService from '../../services/RoutesService';
import RouteForm from '../../views/forms/RouteForm';
import CompanySelect from '../dropdowns/CompanySelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import * as routes from '../../router';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'name' },
  { value: 'company.name' },
  { value: 'buses_count' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`route.fields.${header.value}`);
});

// Actions column
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Routes',
  components: {
    ActionCell,
    CompanySelect,
    RouteForm,
  },
  mixins: [ WithUrlQueryFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      service:              RoutesService,
      itemType:             PoliciesService.itemsTypes.routes,
      itemStringIdentifier: 'name',
      search:               null,
      filters:              {
        companyId: null,
      },
    };
  },
  methods: {
    /**
     * Navigates user to buses routes list page.
     *
     * @param {number} routeId Route identifier to display buses list for
     */
    goToBuses(routeId) {
      this.$router.push({ name: routes.ROUTE_BUSES, query: { routeId } });
    },
  },
};
</script>

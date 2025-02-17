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
      <v-layout v-show="policies.canCreate(itemType)">
        <v-spacer/>
        <v-btn color="primary"
               @click="openModalForm({})"
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
          <td>{{ props.item.bin }}</td>
          <td>{{ props.item.account_number }}</td>
          <td>{{ props.item.contact_information }}</td>
          <ActionCell :item-type="policies.itemsTypes.buses"
                      :intention="policies.intentions.get"
                      class="text-xs-right"
                      @activate="goToBuses(props.item.id)"

          >
            {{ props.item.buses_count }}
          </ActionCell>
          <ActionCell :item-type="policies.itemsTypes.drivers"
                      :intention="policies.intentions.get"
                      class="text-xs-right"
                      @activate="goToDrivers(props.item.id)"

          >
            {{ props.item.drivers_count }}
          </ActionCell>
          <ActionCell :item-type="policies.itemsTypes.routes"
                      :intention="policies.intentions.get"
                      class="text-xs-right"
                      @activate="goToRoutes(props.item.id)"

          >
            {{ props.item.routes_count }}
          </ActionCell>
          <ActionCell :item-type="policies.itemsTypes.users"
                      :intention="policies.intentions.get"
                      class="text-xs-right"
                      @activate="goToUsers(props.item.id)"

          >
            {{ props.item.users_count }}
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
              <v-btn v-show="policies.canSeeList(policies.itemsTypes.routeSheets)"
                     :title="$t('routeSheet.name')"
                     flat
                     icon
                     class="mx-0"
                     @click.stop="goToRouteSheets(props.item.id)"
              >
                <v-icon>today</v-icon>
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

      <CompanyForm :visible="editModalVisible"
                   :value="itemToEdit"
                   @close="closeModalForm"
                   @saved="reloadTable"
      />
    </v-flex>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import * as routes from '../../router';
import CompaniesService from '../../services/CompaniesService';
import CompanyForm from '../../views/forms/CompanyForm';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import ActionCell from './components/ActionCell';
import PoliciesService from '../../services/PoliciesService';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'name' },
  { value: 'bin' },
  { value: 'account_number' },
  { value: 'contact_information' },
  { value: 'buses_count' },
  { value: 'drivers_count' },
  { value: 'routes_count' },
  { value: 'users_count' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`company.fields.${header.value}`);
});

// Actions column
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Companies',
  components: {
    ActionCell,
    CompanyForm,
  },
  mixins: [ CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      search:               null,
      service:              CompaniesService,
      itemType:             PoliciesService.itemsTypes.companies,
      itemStringIdentifier: 'name',
    };
  },
  methods: {
    /**
     * Navigates user to company users list page.
     *
     * @param {number} companyId Company identifier to display users list for
     */
    goToUsers(companyId) {
      this.$router.push({ name: routes.ROUTE_USERS, query: { companyId } });
    },
    /**
     * Navigates user to company routes list page.
     *
     * @param {number} companyId Company identifier to display routes list for
     */
    goToRoutes(companyId) {
      this.$router.push({ name: routes.ROUTE_ROUTES, query: { companyId } });
    },
    /**
     * Navigates user to buses list page.
     *
     * @param {number} companyId Company identifier to display buses list for
     */
    goToBuses(companyId) {
      this.$router.push({ name: routes.ROUTE_BUSES, query: { companyId } });
    },
    /**
     * Navigates user to drivers list page.
     *
     * @param {number} companyId Company identifier to display drivers list for
     */
    goToDrivers(companyId) {
      this.$router.push({ name: routes.ROUTE_DRIVERS, query: { companyId } });
    },
    /**
     * Navigates user to route sheets list page.
     *
     * @param {number} companyId Company identifier to display route sheets list for
     */
    goToRouteSheets(companyId) {
      this.$router.push({ name: routes.ROUTE_ROUTE_SHEETS, query: { companyId } });
    },
  },
};
</script>

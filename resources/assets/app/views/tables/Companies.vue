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
                    item-key="id"
                    class="elevation-1"
                    hide-actions
      >
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
            {{ props.item.name }}
          </td>
          <td>{{ props.item.bin }}</td>
          <td>{{ props.item.account_number }}</td>
          <td>{{ props.item.contact_information }}</td>
          <td class="action-cell text-xs-right"
              @click.stop="goToBuses(props.item.id)"
          >
            {{ props.item.buses_count }}
          </td>
          <td class="action-cell text-xs-right"
              @click.stop="goToDrivers(props.item.id)"
          >
            {{ props.item.drivers_count }}
          </td>
          <td class="action-cell text-xs-right"
              @click.stop="goToRoutes(props.item.id)"
          >
            {{ props.item.routes_count }}
          </td>
          <td class="action-cell text-xs-right"
              @click.stop="goToUsers(props.item.id)"
          >
            {{ props.item.users_count }}
          </td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="openModalForm(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
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

      <CompanyForm
        :visible="editModalVisible"
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
import CompaniesService from '../../services/CompaniesService';
import CompanyForm from '../../views/forms/CompanyForm';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';

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
headers.push({ text: '', sortable: false });

export default {
  name:       'Companies',
  components: {
    CompanyForm,
  },
  mixins: [ CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      search:               null,
      service:              CompaniesService,
      itemType:             'company',
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
  },
};
</script>

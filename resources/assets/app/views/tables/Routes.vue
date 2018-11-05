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
        <CompanySelect v-model="companyId"
                       class="mr-3"
                       clearable
                       @input="filterCompany"
        />
        <v-text-field
          v-model="filter"
          append-icon="search"
          label="Поиск"
          hide-details
          single-line
          clearable
        />
        <v-btn color="primary"
               @click="openRouteModal({})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :items="routes"
                    :search="filter"
                    :no-results-text="$t('tables.noResults')"
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
              @click.stop="openRouteModal(props.item)"
          >
            {{ props.item.name }}
          </td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.buses_count }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="openRouteModal(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="deleteRoute(props.item)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>

        <template slot="no-data">
          <div class="no-data subheading">
            <b>{{ $t('tables.noResults') }}</b>
          </div>
        </template>

      </v-data-table>

      <RouteForm
        :visible="routeModalVisible"
        :value="routeToEdit"
        @close="closeRouteModal"
        @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import RoutesService from '../../services/RoutesService';
import RouteForm from '../../views/forms/RouteForm';
import UserInteractionService from '../../services/UserInteractionService';
import AlertsService from '../../services/AlertsService';
import CompanySelect from '../dropdowns/CompanySelect';

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
headers.push({ text: '', sortable: false });

export default {
  name:       'Routes',
  components: {
    CompanySelect,
    RouteForm,
  },
  data() {
    return {
      headers,
      filter:            null,
      routeModalVisible: false,
      routeToEdit:       {},
      companyId:         null,
    };
  },
  computed: {
    routes() {
      const routes = RoutesService.getRoutes();

      if (!this.companyId) {
        return routes;
      }

      return routes.filter(route => route.company_id === this.companyId);
    },
  },
  watch: {
    companyId() {
      this.$forceUpdate();
    },
    $route(to) {
      this.parseCompanyFromRoute(to);
    },
  },
  mounted() {
    this.parseCompanyFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses company filter parameter from route query.
     *
     * @param {*} route Route to retrieve company identifier from.
     */
    parseCompanyFromRoute(route) {
      this.companyId = Number.parseInt(route.query.companyId, 10);
    },
    /**
     * Performs filtering of routes by given company.
     */
    filterCompany() {
      const query = Object.assign({}, this.$route.query);

      // Replace company identifier parameter in current route query
      query.companyId = this.companyId || null;
      this.$router.push({ to: this.$route.name, query });
    },
    /**
     * Reloads table data.
     */
    reloadTable() {
      RoutesService.readRoutes();
    },
    /**
     * Opens company modal window to create\edit route.
     *
     * @param {Route} routeToEdit Route to edit
     */
    openRouteModal(routeToEdit) {
      this.routeToEdit = routeToEdit;
      this.routeModalVisible = true;
    },
    /**
     * Closes route details modal window.
     */
    closeRouteModal() {
      this.routeModalVisible = false;
      this.routeToEdit = {};
    },
    /**
     * Deletes route.
     *
     * @param {Route} route Route to delete
     */
    deleteRoute(route) {
      UserInteractionService.handleConfirm({
        message: this.$i18n.t('route.deleteConfirm', { route_name: `${route.name}` }),
      })
        .then(() => {
          RoutesService.delete(route)
            .then(() => {
              AlertsService.info(this.$i18n.t('common.notifications.recordDeleted'));
              this.reloadTable();
            })
            .catch(() => {});
        })
        .catch(() => {});
    },
  },
};
</script>

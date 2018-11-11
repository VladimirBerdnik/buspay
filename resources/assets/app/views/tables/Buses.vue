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
          v-model="filter"
          :label="$t('common.placeholders.search')"
          append-icon="search"
          hide-details
          single-line
          clearable
          class="mr-3"
        />
        <CompanySelect v-model="filters.companyId"
                       class="mr-3"
                       @input="switchCompany"
        />
        <RouteSelect v-model="filters.routeId"
                     :company-id="filters.companyId"
                     @input="switchRoute"
        />
        <v-btn color="primary"
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
                    :search="filter"
                    item-key="id"
                    class="elevation-1"
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
            {{ props.item.state_number }}
          </td>
          <td>{{ props.item.model_name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.route.name }}</td>
          <td class="action-cell text-xs-right"
              @click.stop="goToDrivers(props.item.id)"
          >
            {{ props.item.drivers_count }}
          </td>
          <td class="text-xs-right">{{ props.item.validators_count }}</td>
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

      <BusForm
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
import BusesService from '../../services/BusesService';
import CompanySelect from '../dropdowns/CompanySelect';
import BusForm from '../forms/BusForm';
import WithCompanyFilterMixin from '../../mixins/WithCompanyFilterMixin';
import RouteSelect from '../dropdowns/RouteSelect';
import WithRouteFilterMixin from '../../mixins/WithRouteFilterMixin';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';

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
headers.push({ text: '', sortable: false });

export default {
  name:       'Buses',
  components: {
    RouteSelect,
    CompanySelect,
    BusForm,
  },
  mixins: [ WithCompanyFilterMixin, WithRouteFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      filter:               null,
      service:              BusesService,
      itemType:             'bus',
      itemStringIdentifier: 'state_number',
    };
  },
  computed: {
    items() {
      let items = this.service.get();

      const filters = {
        company_id: this.filters.companyId,
        route_id:   this.filters.routeId,
      };

      Object.entries(filters).forEach(entry => {
        const [ filterField, value ] = entry;

        if (!value) {
          return;
        }
        items = items.filter(item => item[filterField] === value);
      });

      return items;
    },
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

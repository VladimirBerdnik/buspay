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
        <CompanySelect v-model="companyId"
                       @input="switchCompany"
        />
        <v-btn color="primary"
               @click="openModalForm({company_id: companyId})"
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
                    :no-results-text="$t('tables.noResults')"
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
            {{ props.item.full_name }}
          </td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.bus.state_number }}</td>
          <td>{{ props.item.card.card_number }}</td>
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

        <template slot="no-data">
          <div class="no-data subheading">
            <b>{{ $t('tables.noResults') }}</b>
          </div>
        </template>

      </v-data-table>

      <!--<DriverForm-->
      <!--:visible="editModalVisible"-->
      <!--:value="itemToEdit"-->
      <!--@close="closeModalForm"-->
      <!--@saved="reloadTable"-->
      <!--/>-->
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import DriversService from '../../services/DriversService';
// import DriverForm from '../../views/forms/DriverForm';
import CompanySelect from '../dropdowns/CompanySelect';
import WithCompanyFilterMixin from '../../mixins/WithCompanyFilterMixin';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';

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
headers.push({ text: '', sortable: false });

export default {
  name:       'Drivers',
  components: {
    CompanySelect,
    // DriverForm,
  },
  mixins: [ WithCompanyFilterMixin, CRUDTableMixin ],
  data() {
    return {
      headers,
      filter:               null,
      service:              DriversService,
      itemType:             'driver',
      itemStringIdentifier: 'full_name',
    };
  },
  computed: {
    items() {
      const drivers = DriversService.get();

      if (!this.companyId) {
        return drivers;
      }

      return drivers.filter(driver => driver.company_id === this.companyId);
    },
  },
};
</script>

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
                       @input="switchCompany"
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
               @click="openBusModal({company_id: companyId})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :items="buses"
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
              @click.stop="openBusModal(props.item)"
          >
            {{ props.item.state_number }}
          </td>
          <td>{{ props.item.model_name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td>{{ props.item.route.name }}</td>
          <td>{{ props.item.drivers_count }}</td>
          <td>{{ props.item.validators_count }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="openBusModal(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="deleteBus(props.item)"
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
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import BusesService from '../../services/BusesService';
import UserInteractionService from '../../services/UserInteractionService';
import AlertsService from '../../services/AlertsService';
import CompanySelect from '../dropdowns/CompanySelect';
import WithCompanyFilterMixin from '../../mixins/WithCompanyFilterMixin';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'model_name' },
  { value: 'state_number' },
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
    CompanySelect,
  },
  mixins: [WithCompanyFilterMixin],
  data() {
    return {
      headers,
      filter:          null,
      busModalVisible: false,
      busToEdit:       {},
    };
  },
  computed: {
    buses() {
      const buses = BusesService.get();

      if (!this.companyId) {
        return buses;
      }

      return buses.filter(bus => bus.company_id === this.companyId);
    },
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      BusesService.read();
    },
    /**
     * Opens company modal window to create\edit bus.
     *
     * @param {Bus} busToEdit Bus to edit
     */
    openBusModal(busToEdit) {
      this.busToEdit = busToEdit;
      this.busModalVisible = true;
    },
    /**
     * Closes bus details modal window.
     */
    closeBusModal() {
      this.busModalVisible = false;
      this.busToEdit = {};
    },
    /**
     * Deletes bus.
     *
     * @param {Bus} bus Bus to delete
     */
    deleteBus(bus) {
      UserInteractionService.handleConfirm({
        message: this.$i18n.t('bus.deleteConfirm', { state_number: `${bus.state_number}` }),
      })
        .then(() => {
          BusesService.delete(bus)
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

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
          append-icon="search"
          label="Поиск"
          hide-details
          single-line
          clearable
        />
        <v-btn color="primary"
               @click="openCompanyModal({})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :items="companies"
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
              @click.stop="openCompanyModal(props.item)"
          >
            {{ props.item.name }}
          </td>
          <td>{{ props.item.bin }}</td>
          <td>{{ props.item.account_number }}</td>
          <td>{{ props.item.contact_information }}</td>
          <td>{{ props.item.buses_count }}</td>
          <td>{{ props.item.drivers_count }}</td>
          <td>{{ props.item.routes_count }}</td>
          <td>{{ props.item.users_count }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="openCompanyModal(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="deleteCompany(props.item)"
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

      <CompanyForm
        :visible="companyModalVisible"
        :value="companyToEdit"
        @close="closeCompanyModal"
        @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import CompaniesService from '../../services/CompaniesService';
import CompanyForm from '../../views/forms/CompanyForm';
import UserInteractionService from '../../services/UserInteractionService';
import AlertsService from '../../services/AlertsService';

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
  data() {
    return {
      headers,
      filter:              null,
      companyModalVisible: false,
      companyToEdit:       {},
    };
  },
  computed: {
    companies: () => CompaniesService.getCompanies(),
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      CompaniesService.readCompanies();
    },
    /**
     * Opens company modal window to create\edit company.
     *
     * @param {Company} companyToEdit Company to edit
     */
    openCompanyModal(companyToEdit) {
      this.companyToEdit = companyToEdit;
      this.companyModalVisible = true;
    },
    /**
     * Closes company details modal window.
     */
    closeCompanyModal() {
      this.companyModalVisible = false;
      this.companyToEdit = {};
    },
    /**
     * Deletes company.
     *
     * @param {Company} company Company to delete
     */
    deleteCompany(company) {
      UserInteractionService.handleConfirm({
        message: this.$i18n.t('company.deleteConfirm', { company: company.name }),
      })
        .then(() => {
          CompaniesService.delete(company)
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

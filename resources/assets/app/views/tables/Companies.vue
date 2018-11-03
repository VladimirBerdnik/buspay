<template>
  <v-data-table :headers="headers"
                :items="companies"
                item-key="id"
                class="elevation-1"
                hide-actions
  >
    <v-progress-linear slot="progress"
                       color="blue"
                       indeterminate/>

    <template slot="headers"
              slot-scope="props"
    >
      <tr>
        <th v-for="header in props.headers"
            :key="header.text"
        >
          {{ header.text }}
        </th>
      </tr>
    </template>

    <template
      slot="items"
      slot-scope="props"
    >
      <td>{{ props.item.id }}</td>
      <td>{{ props.item.name }}</td>
      <td>{{ props.item.bin }}</td>
      <td>{{ props.item.account_number }}</td>
      <td>{{ props.item.contact_information }}</td>
      <td>{{ props.item.buses_count }}</td>
      <td>{{ props.item.drivers_count }}</td>
      <td>{{ props.item.routes_count }}</td>
    </template>

    <template slot="no-data">
      <div class="no-data subheading">
        <b>{{ $t('tables.noResults') }}</b>
      </div>
    </template>

  </v-data-table>
</template>

<script>
import i18n from '../../lang/i18n';
import CompaniesService from '../../services/CompaniesService';

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
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`company.fields.${header.value}`);
});

export default {
  name: 'Companies',
  data() {
    return {
      headers,
    };
  },
  computed: {
    companies: () => CompaniesService.getCompanies(),
  },
  mounted() {
    this.reloadTable();
  },
  methods: {
    reloadTable() {
      CompaniesService.getCompanies(true);
    },
  },
};
</script>

<style scoped>

</style>

<template>
  <v-data-table :headers="headers"
                :items="cardTypes"
                item-key="id"
                class="elevation-1"
                hide-actions
  >
    <v-progress-linear slot="progress"
                       color="blue"
                       indeterminate
    />

    <template slot="items"
              slot-scope="props"
    >
      <td>{{ props.item.id }}</td>
      <td>{{ props.item.name }}</td>
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
import CardTypesService from '../../services/CardTypesService';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'name' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`cardType.fields.${header.value}`);
});

export default {
  name: 'CardTypes',
  data() {
    return {
      headers,
    };
  },
  computed: {
    cardTypes: () => CardTypesService.get(),
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      CardTypesService.read();
    },
  },
};
</script>

<style scoped>

</style>

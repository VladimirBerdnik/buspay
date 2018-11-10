<template>
  <div row>
    <v-data-table :headers="headers"
                  :rows-per-page-items="datatablesConfig.serverSidePaginatorValues"
                  :pagination.sync="pagination"
                  :total-items="totalItems"
                  :custom-sort="items => items"
                  :custom-filter="items => items"
                  :items="items"
                  :loading="loadingInProgress"
                  item-key="id"
                  class="elevation-1"
    >
      <v-progress-linear slot="progress"
                         color="blue"
                         indeterminate
      />

      <template slot="items"
                slot-scope="props"
      >
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.cardType.name }}</td>
        <td>{{ props.item.card_number }}</td>
        <td>{{ props.item.uin }}</td>
        <td><v-icon>{{ props.item.active ? 'check_box_outline_blank' : 'check_box' }}</v-icon></td>
      </template>

    </v-data-table>

    <div class="text-xs-center pt-2">
      <v-pagination v-model="pagination.page"
                    :length="totalPages"
                    :total-visible="datatablesConfig.paginatorLength"
                    dark
      />
    </div>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import CardsService from '../../services/CardsService';
import PaginatedTableMixin from '../../mixins/PaginatedTableMixin';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'card_type_id' },
  { value: 'card_number' },
  { value: 'uin' },
  { value: 'active' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`card.fields.${header.value}`);
});

export default {
  name:   'Cards',
  mixins: [PaginatedTableMixin],
  data() {
    return {
      headers,
      service: CardsService,
    };
  },
};
</script>

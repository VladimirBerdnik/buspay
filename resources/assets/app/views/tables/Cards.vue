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
          class="mr-3"
        />
        <CardTypesSelect v-model="filters.cardTypeId"
                         @input="switchCardType"
        />
      </v-layout>
    </v-flex>
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
        <td><v-icon>{{ props.item.active ? 'check_box' : 'check_box_outline_blank' }}</v-icon></td>
        <td>{{ props.item.synchronized_at | timeStamp }}</td>
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
import CardTypesSelect from '../dropdowns/CardTypeSelect';
import WithCardTypeFilterMixin from '../../mixins/WithCardTypeFilterMixin';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'card_type_id' },
  { value: 'card_number' },
  { value: 'uin' },
  { value: 'active' },
  { value: 'synchronized_at' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`card.fields.${header.value}`);
});

export default {
  name:       'Cards',
  components: { CardTypesSelect },
  mixins:     [ PaginatedTableMixin, WithCardTypeFilterMixin ],
  data() {
    return {
      headers,
      service: CardsService,
      search:  null,
    };
  },
};
</script>

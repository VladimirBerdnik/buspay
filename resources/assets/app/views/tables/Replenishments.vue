<template>
  <div>
    <v-flex class="mb-3"
            xs12
    >
      <v-layout row
                wrap
      >
        <v-text-field v-model="search"
                      :label="$t('common.placeholders.search')"
                      append-icon="search"
                      hide-details
                      single-line
                      clearable
                      class="pr-3 flex xs6 sm4 md3"
        />
        <DateSelect v-model="activeFrom"
                    :label="$t('replenishment.active_from')"
                    :default-hours="0"
                    :default-minutes="0"
                    :default-seconds="0"
                    time-as-hint
                    class="pr-3 flex xs6 sm4 md3 v-input"
        />
        <DateSelect v-model="activeTo"
                    :label="$t('replenishment.active_to')"
                    :default-hours="23"
                    :default-minutes="59"
                    :default-seconds="59"
                    time-as-hint
                    class="pr-3 flex xs6 sm4 md3 v-input"
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
      <template slot="headerCell"
                slot-scope="props"
      >
        <v-btn v-if="props.header.actionsColumn"
               :title="$t('common.buttons.refresh')"
               flat
               icon
               @click="reloadTable"
        >
          <v-icon>cached</v-icon>
        </v-btn>
        <template v-else>
          {{ props.header.text }}
        </template>
      </template>

      <v-progress-linear slot="progress"
                         color="blue"
                         indeterminate
      />

      <template slot="items"
                slot-scope="props"
      >
        <td>{{ props.item.id }}</td>
        <ActionCell @activate="goToCardDetails(props.item.card.card_number)">
          {{ props.item.card.card_number }}
        </ActionCell>
        <td>{{ props.item.amount }}</td>
        <td>{{ props.item.replenished_at | timeStamp }}</td>
        <td>{{ props.item.external_id }}</td>
        <td/>
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
import * as router from '../../router/index';
import ReplenishmentsService from '../../services/ReplenishmentsService';
import PaginatedTableMixin from '../../mixins/PaginatedTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import ActionCell from './components/ActionCell';
import DateSelect from '../dropdowns/DateSelect';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'card.card_number', sortable: false },
  { value: 'amount' },
  { value: 'replenished_at' },
  { value: 'external_id' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`replenishment.fields.${header.value}`);
});

headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Replenishments',
  components: { DateSelect, ActionCell },
  mixins:     [ PaginatedTableMixin, WithUrlQueryFilterMixin ],
  data() {
    return {
      headers,
      service:    ReplenishmentsService,
      pagination: {
        sortBy:     'replenished_at',
        descending: true,
      },
    };
  },
  methods: {
    goToCardDetails(cardNumber) {
      this.$router.push({
        name:   router.ROUTE_CARD_DETAILS,
        params: { cardNumber },
      });
    },
  },
};
</script>

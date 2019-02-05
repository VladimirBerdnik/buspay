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
                      class="mr-3"
        />
        <CardTypesSelect v-model="filters.cardTypeId"
                         class="mr-3"
                         @input="updateQueryParameters"
        />
        <ValidatorSelect v-model="filters.validatorId"
                         class="mr-3"
                         @input="updateQueryParameters"
        />
        <TariffSelect v-model="filters.tariffId"
                      class="mr-3"
                      @input="updateQueryParameters"
        />
        <CompanySelect v-model="filters.companyId"
                       class="mr-3"
                       @input="updateQueryParameters"
        />
        <DriverSelect v-model="filters.driverId"
                      :company-id="filters.companyId"
                      class="mr-3"
                      @input="updateQueryParameters"
        />
        <BusSelect v-model="filters.busId"
                   :company-id="filters.companyId"
                   class="mr-3"
                   @input="updateQueryParameters"
        />
        <DateSelect v-model="activeFrom"
                    :label="$t('transaction.active_from')"
                    :default-hours="0"
                    :default-minutes="0"
                    :default-seconds="0"
                    time-as-hint
                    class="mr-3 v-input"
        />
        <DateSelect v-model="activeTo"
                    :label="$t('transaction.active_to')"
                    :default-hours="23"
                    :default-minutes="59"
                    :default-seconds="59"
                    time-as-hint
                    class="v-input"
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
        <td>{{ props.item.card.cardType.name }}</td>
        <td>{{ props.item.validator.serial_number }}</td>
        <td>{{ props.item.validator.bus.state_number }}</td>
        <td>{{ props.item.validator.bus.company.name }}</td>
        <td>{{ props.item.validator.bus.route.name }}</td>
        <td>{{ props.item.tariff.name }}</td>
        <td>{{ props.item.authorized_at | timeStamp }}</td>
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
import TransactionsService from '../../services/TransactionsService';
import PaginatedTableMixin from '../../mixins/PaginatedTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import ActionCell from './components/ActionCell';
import DateSelect from '../dropdowns/DateSelect';
import CompanySelect from '../dropdowns/CompanySelect';
import BusSelect from '../dropdowns/BusSelect';
import CardTypesSelect from '../dropdowns/CardTypeSelect';
import ValidatorSelect from '../dropdowns/ValidatorSelect';
import TariffSelect from '../dropdowns/TariffSelect';
import DriverSelect from '../dropdowns/DriverSelect';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'card.card_number', sortable: false },
  { value: 'amount' },
  { value: 'card.cardType.name', sortable: false },
  { value: 'validator.serial_number', sortable: false },
  { value: 'validator.bus.state_number', sortable: false },
  { value: 'validator.bus.company.name', sortable: false },
  { value: 'validator.bus.route.name', sortable: false },
  { value: 'tariff.name', sortable: false },
  { value: 'authorized_at' },
  { value: 'external_id' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`transaction.fields.${header.value}`);
});

headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Transactions',
  components: {
    DriverSelect,
    TariffSelect,
    ValidatorSelect,
    CardTypesSelect,
    BusSelect,
    CompanySelect,
    DateSelect,
    ActionCell,
  },
  mixins: [ PaginatedTableMixin, WithUrlQueryFilterMixin ],
  data() {
    return {
      headers,
      service:    TransactionsService,
      pagination: {
        sortBy:     'authorized_at',
        descending: true,
      },
      filters: {
        companyId:   null,
        busId:       null,
        cardTypeId:  null,
        validatorId: null,
        tariffId:    null,
        driverId:    null,
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

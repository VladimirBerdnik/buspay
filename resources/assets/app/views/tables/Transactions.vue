<template>
  <div>
    <v-flex class="mb-3"
            xs12
    >
      <v-layout row
                wrap
      >
        <v-text-field v-model="search"
                      :label="
                        `${$t('common.placeholders.search')}: ${$t('card.fields.card_number')}`
                      "
                      append-icon="search"
                      hide-details
                      single-line
                      clearable
                      class="pr-3 flex xs6 sm4 md3"
        />
        <CardTypesSelect v-if="policies.canSeeList(policies.itemsTypes.cardTypes)"
                         v-model="filters.cardTypeId"
                         class="pr-3 flex xs6 sm4 md3"
                         @input="updateQueryParameters"
        />
        <ValidatorSelect v-if="policies.canSeeList(policies.itemsTypes.validators)"
                         v-model="filters.validatorId"
                         class="pr-3 flex xs6 sm4 md3"
                         @input="updateQueryParameters"
        />
        <TariffSelect v-if="policies.canSeeList(policies.itemsTypes.tariffs)"
                      v-model="filters.tariffId"
                      class="pr-3 flex xs6 sm4 md3"
                      @input="updateQueryParameters"
        />
        <CompanySelect v-if="policies.canSeeList(policies.itemsTypes.companies)"
                       v-model="filters.companyId"
                       class="pr-3 flex xs6 sm4 md3"
                       @input="updateQueryParameters"
        />
        <BusSelect v-if="policies.canSeeList(policies.itemsTypes.buses)"
                   v-model="filters.busId"
                   :company-id="filters.companyId"
                   class="pr-3 flex xs6 sm4 md3"
                   @input="updateQueryParameters"
        />
        <DateSelect v-model="activeFrom"
                    :label="$t('transaction.active_from')"
                    :default-hours="0"
                    :default-minutes="0"
                    :default-seconds="0"
                    time-as-hint
                    class="pr-3 flex xs6 sm4 md3 v-input"
        />
        <DateSelect v-model="activeTo"
                    :label="$t('transaction.active_to')"
                    :default-hours="23"
                    :default-minutes="59"
                    :default-seconds="59"
                    time-as-hint
                    class="pr-3 flex xs6 sm4 md3 v-input"
        />
      </v-layout>
      <v-layout row>
        <v-spacer/>
        <v-btn color="success"
               @click="exportRecords"
        >
          <v-icon>file_download</v-icon>
          {{ $t('common.buttons.export') }}
        </v-btn>
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
        <tr :class="{ warning: !props.item.route_sheet_id }">
          <td>{{ props.item.id }}</td>
          <ActionCell :item-type="itemType"
                      :intention="policies.intentions.showTransactionCard"
                      @activate="goToCardDetails(props.item.card.card_number)"
          >
            {{ props.item.card.card_number }}
          </ActionCell>
          <td>{{ props.item.card.cardType.name }}</td>
          <td>{{ props.item.tariff.name }}</td>
          <td>{{ props.item.amount }}</td>
          <td>{{ props.item.route_sheet_id ? props.item.routeSheet.bus.state_number : null }}</td>
          <td>{{ props.item.route_sheet_id ? props.item.routeSheet.route.name : null }}</td>
          <td>{{ props.item.validator.serial_number }}</td>
          <td>{{ props.item.route_sheet_id ? props.item.routeSheet.company.name : null }}</td>
          <td>{{ props.item.authorized_at | timeStamp }}</td>
          <td>{{ props.item.external_id }}</td>
          <td/>
        </tr>
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
import PoliciesService from '../../services/PoliciesService';

const headers = [
  { value: 'id' },
  { value: 'card.card_number', sortable: false },
  { value: 'card.cardType.name', sortable: false },
  { value: 'tariff.name', sortable: false },
  { value: 'amount' },
  { value: 'routeSheet.bus.state_number', sortable: false },
  { value: 'routeSheet.route.name', sortable: false },
  { value: 'validator.serial_number', sortable: false },
  { value: 'routeSheet.company.name', sortable: false },
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
      },
      itemType: PoliciesService.itemsTypes.transactions,
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

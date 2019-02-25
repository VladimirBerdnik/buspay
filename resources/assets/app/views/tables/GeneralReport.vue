<template>
  <div>
    <v-flex class="mb-3"
            xs12
    >
      <v-layout row
                wrap
      >
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.companies)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.company"
                      hide-details
                      class="shrink mr-2"
          />
          <CompanySelect v-model="filters.companyId"
                         :readonly="selectedFields.indexOf(reportFields.company) === -1"
                         class="pr-3 flex"
                         @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.routes)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.route"
                      hide-details
                      class="shrink mr-2"
          />
          <RouteSelect v-model="filters.routeId"
                       :company-id="filters.companyId"
                       :readonly="selectedFields.indexOf(reportFields.route) === -1"
                       class="pr-3 flex"
                       @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.tariffs)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.tariff"
                      hide-details
                      class="shrink mr-2"
          />
          <TariffSelect v-model="filters.tariffId"
                        :readonly="selectedFields.indexOf(reportFields.tariff) === -1"
                        class="pr-3 flex"
                        @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.buses)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.bus"
                      hide-details
                      class="shrink mr-2"
          />
          <BusSelect v-model="filters.busId"
                     :company-id="filters.companyId"
                     :readonly="selectedFields.indexOf(reportFields.bus) === -1"
                     class="pr-3 flex"
                     @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.drivers)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.driver"
                      hide-details
                      class="shrink mr-2"
          />
          <DriverSelect v-model="filters.driverId"
                        :company-id="filters.companyId"
                        :readonly="selectedFields.indexOf(reportFields.driver) === -1"
                        class="pr-3 flex"
                        @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.cardTypes)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.cardType"
                      hide-details
                      class="shrink mr-2"
          />
          <CardTypesSelect v-model="filters.cardTypeId"
                           :readonly="selectedFields.indexOf(reportFields.cardType) === -1"
                           class="pr-3 flex"
                           @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.validators)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.validator"
                      hide-details
                      class="shrink mr-2"
          />
          <ValidatorSelect v-model="filters.validatorId"
                           :readonly="selectedFields.indexOf(reportFields.validator) === -1"
                           class="pr-3 flex"
                           @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout class="xs12 sm8 md6 flex">
          <v-checkbox v-model="selectedFields"
                      :value="reportFields.date"
                      hide-details
                      class="shrink mr-2"
          />
          <DateSelect v-model="activeFrom"
                      :readonly="selectedFields.indexOf(reportFields.date) === -1"
                      :label="$t('transaction.active_from')"
                      :default-hours="0"
                      :default-minutes="0"
                      :default-seconds="0"
                      time-as-hint
                      class="pr-3 flex v-input"
          />
          <DateSelect v-model="activeTo"
                      :readonly="selectedFields.indexOf(reportFields.date) === -1"
                      :label="$t('transaction.active_to')"
                      :default-hours="23"
                      :default-minutes="59"
                      :default-seconds="59"
                      time-as-hint
                      class="pr-3 flex v-input"
          />
        </v-layout>
      </v-layout>
      <v-layout row>
        <v-spacer/>
        <v-btn color="success"
               @click="() => {}"
        >
          <v-icon>file_download</v-icon>
          {{ $t('common.buttons.export') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-data-table :headers="headers"
                  :rows-per-page-items="datatablesConfig.paginatorValues"
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
        <td/>
      </template>

    </v-data-table>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import TransactionsService from '../../services/TransactionsService';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import ActionCell from './components/ActionCell';
import DateSelect from '../dropdowns/DateSelect';
import CompanySelect from '../dropdowns/CompanySelect';
import BusSelect from '../dropdowns/BusSelect';
import CardTypesSelect from '../dropdowns/CardTypeSelect';
import ValidatorSelect from '../dropdowns/ValidatorSelect';
import TariffSelect from '../dropdowns/TariffSelect';
import RouteSelect from '../dropdowns/RouteSelect';
import DriverSelect from '../dropdowns/DriverSelect';
import reportFields from '../../enums/GeneralReportFields';
// TODO: Connect SimpleTableMixin instead:
import datatablesConfig from '../../config/datatables';

const headers = [
  { value: 'id' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`report.general.fields.${header.value}`);
});

export default {
  name:       'GeneralReport',
  components: {
    DriverSelect,
    RouteSelect,
    TariffSelect,
    ValidatorSelect,
    CardTypesSelect,
    BusSelect,
    CompanySelect,
    DateSelect,
    ActionCell,
  },
  mixins: [WithUrlQueryFilterMixin],
  data() {
    return {
      datatablesConfig,
      reportFields,
      selectedFields: [],
      headers,
      items:          [
        { id: 1 },
      ],
      service: TransactionsService,
      filters: {
        companyId:   null,
        routeId:     null,
        busId:       null,
        cardTypeId:  null,
        driverId:    null,
        validatorId: null,
        tariffId:    null,
      },
      activeFrom:        null,
      activeTo:          null,
      // TODO improve
      loadingInProgress: false,
    };
  },
};
</script>

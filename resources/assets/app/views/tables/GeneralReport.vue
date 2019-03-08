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
                      :value="availableFields.company"
                      hide-details
                      class="shrink mr-2"
          />
          <CompanySelect v-model="filters.companyId"
                         class="pr-3 flex"
                         @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.routes)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.route"
                      hide-details
                      class="shrink mr-2"
          />
          <RouteSelect v-model="filters.routeId"
                       :company-id="filters.companyId"
                       class="pr-3 flex"
                       @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.tariffs)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.tariff"
                      hide-details
                      class="shrink mr-2"
          />
          <TariffSelect v-model="filters.tariffId"
                        class="pr-3 flex"
                        @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.buses)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.bus"
                      hide-details
                      class="shrink mr-2"
          />
          <BusSelect v-model="filters.busId"
                     :company-id="filters.companyId"
                     class="pr-3 flex"
                     @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.drivers)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.driver"
                      hide-details
                      class="shrink mr-2"
          />
          <DriverSelect v-model="filters.driverId"
                        :company-id="filters.companyId"
                        class="pr-3 flex"
                        @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.cardTypes)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.cardType"
                      hide-details
                      class="shrink mr-2"
          />
          <CardTypesSelect v-model="filters.cardTypeId"
                           class="pr-3 flex"
                           @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout v-if="policies.canSeeList(policies.itemsTypes.validators)"
                  class="xs6 sm4 md3 flex"
        >
          <v-checkbox v-model="selectedFields"
                      :value="availableFields.validator"
                      hide-details
                      class="shrink mr-2"
          />
          <ValidatorSelect v-model="filters.validatorId"
                           class="pr-3 flex"
                           @input="updateQueryParameters"
          />
        </v-layout>
        <v-layout class="xs6 sm4 md3 flex">
          <v-checkbox v-model="selectedFields"
                      :label="$t('reports.general.labels.byDays')"
                      :value="availableFields.date"
                      class="pr-3 flex"
          />
        </v-layout>
        <v-layout class="xs6 sm4 md3 flex">
          <v-checkbox v-model="selectedFields"
                      :label="$t('reports.general.labels.byHour')"
                      :value="availableFields.hour"
                      class="pr-3 flex"
          />
        </v-layout>
        <v-layout class="xs12 sm8 md6 flex">
          <DateSelect v-model="activeFrom"
                      :label="$t('transaction.active_from')"
                      :default-hours="0"
                      :default-minutes="0"
                      :default-seconds="0"
                      time-as-hint
                      class="pr-3 flex v-input"
          />
          <DateSelect v-model="activeTo"
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
        {{ $t('reports.general.labels.refreshDescription') }}
        <v-spacer/>
        <v-btn color="success"
               @click="reloadTable"
        >
          <v-icon>find_replace</v-icon>
          {{ $t('common.buttons.refresh') }}
        </v-btn>
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
        <td v-for="reportField in reportFields"
            :key="reportField"
        >
          {{ props.item[reportField] }}
        </td>
        <td>{{ props.item.transactionsCount }}</td>
        <td>{{ props.item.transactionsSum }}</td>
      </template>

    </v-data-table>
  </div>
</template>

<script>
import defaults from '../../config/defaults';
import i18n from '../../lang/i18n';
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
import availableFields from '../../enums/GeneralReportFields';
import datatablesConfig from '../../config/datatables';
import GeneralReportService from '../../services/GeneralReportService';

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
      availableFields,
      selectedFields: [
        availableFields.date,
      ],
      reportFields: [],
      service:      GeneralReportService,
      filters:      {
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
      loadingInProgress: false,
    };
  },
  computed: {
    headers() {
      const headers = [];

      Object.values(this.reportFields).forEach(field => {
        headers.push({ value: field });
      });

      headers.push({ value: 'transactionsCount' });
      headers.push({ value: 'transactionsSum' });

      // Table headers translates
      Object.values(headers).forEach((header, key) => {
        headers[key].text = i18n.t(`reports.general.fields.${header.value}`);
      });

      return headers;
    },
    /**
     * Handled items list.
     *
     * @return {Object[]}
     */
    items() {
      return this.service.get();
    },
  },
  mounted() {
    // Unexpectedly doesn't work if this value is set in data object
    this.activeFrom = defaults.activeFrom;
  },
  methods: {
    /**
     * Reloads table data.
     */
    async reloadTable() {
      if (this.loadingInProgress) {
        return;
      }

      this.loadingInProgress = true;

      this.reportFields = this.selectedFields.slice();

      try {
        const params = Object.assign(
          {},
          { filters: this.filters },
          { fields: this.reportFields || [] },
          { active_from: this.activeFrom },
          { active_to: this.activeTo }
        );

        await this.service.read(params);
      } catch (exception) {
        // no action required
      } finally {
        this.loadingInProgress = false;
      }
    },
  },
};
</script>

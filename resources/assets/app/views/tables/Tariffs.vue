<template>
  <v-layout row
            wrap
  >
    <v-flex xs12>
      <v-select :items="tariffPeriods"
                :return-object="true"
                v-model="tariffPeriod"
                :label="$t('tariffPeriod.name')"
                item-text="active_from"
                item-value="id"
      >
        <template slot="item"
                  slot-scope="props"
        >
          {{ props.item.active_from | shortDate }}
          - {{ props.item.active_to | shortDate($t('periods.toNow')) }}
        </template>
        <template slot="selection"
                  slot-scope="props"
        >
          {{ props.item.active_from | shortDate }}
          - {{ props.item.active_to | shortDate($t('periods.toNow')) }}
        </template>
      </v-select>
    </v-flex>
    <v-layout child-flex>
      <v-data-table :headers="headers"
                    :rows-per-page-items="datatablesConfig.paginatorValues"
                    :items="items"
                    item-key="id"
                    class="elevation-1"
                    hide-actions
                    xs12
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
          <td v-for="cardType in cardTypes"
              :key="cardType.id"
              class="text-xs-right"
          >
            {{ findTariffFareForCardType(props.item.tariffFares, cardType) }}
          </td>
        </template>

        <template slot="no-data">
          <div class="no-data subheading">
            <b>{{ $t('tables.noResults') }}</b>
          </div>
        </template>

      </v-data-table>
    </v-layout>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import TariffPeriodsService from '../../services/TariffPeriodsService';
import TariffsService from '../../services/TariffsService';
import CardTypesService from '../../services/CardTypesService';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';

export default {
  name:   'Tariffs',
  mixins: [SimpleTableMixin],
  data() {
    return {
      tariffPeriod: null,
      service:      TariffsService,
    };
  },
  computed: {
    cardTypes:     () => CardTypesService.get(),
    tariffPeriods: () => TariffPeriodsService.get(),
    headers() {
      const headers = [
        { value: 'id', text: i18n.t('tariff.fields.id') },
        { value: 'name', text: i18n.t('tariff.fields.name') },
      ];

      this.cardTypes.forEach(cardType => {
        headers.push({ value: cardType.id, text: cardType.name, sortable: false });
      });

      return headers;
    },
  },
  watch: {
    tariffPeriod() {
      this.reloadTable();
    },
  },
  async mounted() {
    await TariffPeriodsService.read();
    this.tariffPeriod = this.tariffPeriods[0] || null;
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      TariffsService.read(this.tariffPeriod);
    },
    /**
     * Searches amount of tariff fare for given card type.
     *
     * @param {TariffFare[]} tariffFares - Tariff fares to find in.
     * @param {CardType} cardType - Card type to find for.
     *
     * @returns {Number|null} Tariff fare amount
     */
    findTariffFareForCardType(tariffFares, cardType) {
      const tariffFare =  tariffFares.find(tariffFare => tariffFare.card_type_id === cardType.id);

      return tariffFare ? tariffFare.amount : null;
    },
  },
};
</script>

<style scoped>

</style>

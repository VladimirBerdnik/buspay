<template>
  <v-data-table :headers="headers"
                :rows-per-page-items="datatablesConfig.paginatorValues"
                :items="items"
                :loading="loadingInProgress"
                item-key="id"
                class="elevation-1"
                hide-actions
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
      <td>{{ props.item.name }}</td>
      <ActionCell :item-type="policies.itemsTypes.cards"
                  :intention="policies.intentions.get"
                  class="text-xs-right"
                  @activate="goToCards(props.item.id)"

      >
        {{ props.item.cards_count }}
      </ActionCell>
      <td/>
    </template>

  </v-data-table>
</template>

<script>
import * as routes from '../../router';
import i18n from '../../lang/i18n';
import CardTypesService from '../../services/CardTypesService';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import ActionCell from './components/ActionCell';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'name' },
  { value: 'cards_count' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`cardType.fields.${header.value}`);
});

headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'CardTypes',
  components: { ActionCell },
  mixins:     [SimpleTableMixin],
  data() {
    return {
      headers,
      service: CardTypesService,
    };
  },
  methods: {
    /**
     * Navigates user to cards list page.
     *
     * @param {number} cardTypeId Card type identifier to display cards list for
     */
    goToCards(cardTypeId) {
      this.$router.push({ name: routes.ROUTE_CARDS, query: { cardTypeId } });
    },
  },
};
</script>

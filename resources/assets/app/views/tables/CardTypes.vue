<template>
  <v-data-table :headers="headers"
                :rows-per-page-items="datatablesConfig.paginatorValues"
                :items="items"
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
      <td class="action-cell text-xs-right"
          @click.stop="goToCards(props.item.id)"
      >
        {{ props.item.cards_count }}
      </td>
    </template>

  </v-data-table>
</template>

<script>
import * as routes from '../../router';
import i18n from '../../lang/i18n';
import CardTypesService from '../../services/CardTypesService';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';

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

export default {
  name:   'CardTypes',
  mixins: [SimpleTableMixin],
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

<style scoped>

</style>

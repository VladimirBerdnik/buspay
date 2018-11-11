<template>
  <v-layout row
            wrap
  >
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
        <CompanySelect v-model="filters.companyId"
                       @input="updateQueryParameters"
        />
        <v-btn color="primary"
               @click="openModalForm({})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :rows-per-page-items="datatablesConfig.paginatorValues"
                    :items="items"
                    :search="search"
                    item-key="id"
                    class="elevation-1"
                    hide-actions
      >
        <v-progress-linear slot="progress"
                           color="blue"
                           indeterminate
        />

        <template
          slot="items"
          slot-scope="props"
        >
          <td>{{ props.item.id }}</td>
          <td class="action-cell"
              @click.stop="openModalForm(props.item)"
          >
            {{ props.item.first_name }}
          </td>
          <td>{{ props.item.last_name }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.role.name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="openModalForm(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="deleteItem(props.item)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>

      </v-data-table>

      <UserForm
        :visible="editModalVisible"
        :value="itemToEdit"
        @close="closeModalForm"
        @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import UsersService from '../../services/UsersService';
import UserForm from '../../views/forms/UserForm';
import CompanySelect from '../dropdowns/CompanySelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';

// Table headers
const headers = [
  { value: 'id' },
  { value: 'first_name' },
  { value: 'last_name' },
  { value: 'email' },
  { value: 'role.name' },
  { value: 'company.name' },
];

// Table headers translates
Object.values(headers).forEach((header, key) => {
  headers[key].text = i18n.t(`user.fields.${header.value}`);
});

// Actions column
headers.push({ text: '', sortable: false });

export default {
  name:       'Users',
  components: {
    CompanySelect,
    UserForm,
  },
  mixins: [ WithUrlQueryFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      service:              UsersService,
      itemType:             'user',
      itemStringIdentifier: 'email',
      search:               null,
      filters:              {
        companyId: null,
      },
    };
  },
  computed: {
    items() {
      let items = this.service.get();

      const filters = {
        company_id: this.filters.companyId,
      };


      Object.entries(filters).forEach(entry => {
        const [ filterField, value ] = entry;

        if (!value) {
          return;
        }
        items = items.filter(item => item[filterField] === value);
      });

      return items;
    },
  },
};
</script>

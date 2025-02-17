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
          class="pr-3 flex xs6 sm4 md3"
        />
        <CompanySelect v-if="policies.canSeeList(policies.itemsTypes.companies)"
                       v-model="filters.companyId"
                       class="pr-3 flex xs6 sm4 md3"
                       @input="updateQueryParameters"
        />
      </v-layout>
      <v-layout v-show="policies.canCreate(itemType)">
        <v-spacer/>
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

        <template
          slot="items"
          slot-scope="props"
        >
          <td>{{ props.item.id }}</td>
          <ActionCell :item-type="itemType"
                      :intention="policies.intentions.show"
                      @activate="openModalForm(props.item)"

          >
            {{ props.item.first_name }}
          </ActionCell>
          <td>{{ props.item.last_name }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.role.name }}</td>
          <td>{{ props.item.company.name }}</td>
          <td class="px-0">
            <div class="cell-buttons">
              <v-btn v-show="policies.canUpdate(policies.itemsTypes.users)"
                     flat
                     icon
                     class="mx-0"
                     @click.stop="openModalForm(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn v-show="policies.canDelete(policies.itemsTypes.users)"
                     flat
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

      <UserForm :visible="editModalVisible"
                :value="itemToEdit"
                @close="closeModalForm"
                @saved="reloadTable"
      />
    </v-flex>
  </div>
</template>

<script>
import i18n from '../../lang/i18n';
import UsersService from '../../services/UsersService';
import UserForm from '../../views/forms/UserForm';
import CompanySelect from '../dropdowns/CompanySelect';
import CRUDTableMixin from '../../mixins/CRUDTableMixin';
import SimpleTableMixin from '../../mixins/SimpleTableMixin';
import WithUrlQueryFilterMixin from '../../mixins/WithUrlQueryFilterMixin';
import PoliciesService from '../../services/PoliciesService';
import ActionCell from './components/ActionCell';

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
headers.push({
  text:          '',
  sortable:      false,
  actionsColumn: true,
  width:         '1%',
});

export default {
  name:       'Users',
  components: {
    ActionCell,
    CompanySelect,
    UserForm,
  },
  mixins: [ WithUrlQueryFilterMixin, CRUDTableMixin, SimpleTableMixin ],
  data() {
    return {
      headers,
      service:              UsersService,
      itemType:             PoliciesService.itemsTypes.users,
      itemStringIdentifier: 'email',
      search:               null,
      filters:              {
        companyId: null,
      },
    };
  },
};
</script>

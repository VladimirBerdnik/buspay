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
        <CompanySelect v-model="companyId"
                       class="mr-3"
                       clearable
                       @input="filterCompany"
        />
        <v-text-field
          v-model="filter"
          append-icon="search"
          label="Поиск"
          hide-details
          single-line
          clearable
        />
        <v-btn color="primary"
               @click="openUserModal({})"
        >
          {{ $t('common.buttons.add') }}
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex child-flex>
      <v-data-table :headers="headers"
                    :items="users"
                    :search="filter"
                    :no-results-text="$t('tables.noResults')"
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
              @click.stop="openUserModal(props.item)"
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
                     @click.stop="openUserModal(props.item)"
              >
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn flat
                     icon
                     class="mx-0"
                     @click.stop="deleteUser(props.item)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>

        <template slot="no-data">
          <div class="no-data subheading">
            <b>{{ $t('tables.noResults') }}</b>
          </div>
        </template>

      </v-data-table>

      <UserForm
        :visible="userModalVisible"
        :value="userToEdit"
        @close="closeUserModal"
        @saved="reloadTable"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import i18n from '../../lang/i18n';
import UsersService from '../../services/UsersService';
import UserForm from '../../views/forms/UserForm';
import UserInteractionService from '../../services/UserInteractionService';
import AlertsService from '../../services/AlertsService';
import CompanySelect from '../dropdowns/CompanySelect';

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
  data() {
    return {
      headers,
      filter:           null,
      userModalVisible: false,
      userToEdit:       {},
      companyId:        null,
    };
  },
  computed: {
    users() {
      const users = UsersService.getUsers();

      if (!this.companyId) {
        return users;
      }

      return users.filter(user => user.company_id === this.companyId);
    },
  },
  watch: {
    companyId() {
      this.$forceUpdate();
    },
    $route(to) {
      this.parseCompanyFromRoute(to);
    },
  },
  mounted() {
    this.parseCompanyFromRoute(this.$route);
  },
  methods: {
    /**
     * Parses company filter parameter from route query.
     *
     * @param {*} route Route to retrieve company identifier from.
     */
    parseCompanyFromRoute(route) {
      this.companyId = Number.parseInt(route.query.companyId, 10);
    },
    /**
     * Performs filtering of users by given company.
     */
    filterCompany() {
      const query = Object.assign({}, this.$route.query);

      // Replace company identifier parameter in current route query
      query.companyId = this.companyId || null;
      this.$router.push({ to: this.$route.name, query });
    },
    /**
     * Reloads table data.
     */
    reloadTable() {
      UsersService.readUsers();
    },
    /**
     * Opens company modal window to create\edit user.
     *
     * @param {User} userToEdit User to edit
     */
    openUserModal(userToEdit) {
      this.userToEdit = userToEdit;
      this.userModalVisible = true;
    },
    /**
     * Closes user details modal window.
     */
    closeUserModal() {
      this.userModalVisible = false;
      this.userToEdit = {};
    },
    /**
     * Deletes user.
     *
     * @param {User} user User to delete
     */
    deleteUser(user) {
      UserInteractionService.handleConfirm({
        message: this.$i18n.t('user.deleteConfirm', { user_name: `${user.first_name} ${user.last_name}` }),
      })
        .then(() => {
          UsersService.delete(user)
            .then(() => {
              AlertsService.info(this.$i18n.t('common.notifications.recordDeleted'));
              this.reloadTable();
            })
            .catch(() => {});
        })
        .catch(() => {});
    },
  },
};
</script>

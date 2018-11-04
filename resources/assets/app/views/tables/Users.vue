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
    UserForm,
  },
  data() {
    return {
      headers,
      filter:           null,
      userModalVisible: false,
      userToEdit:       {},
    };
  },
  computed: {
    users: () => UsersService.getUsers(),
  },
  mounted() {
    this.reloadTable();
  },
  methods: {
    /**
     * Reloads table data.
     */
    reloadTable() {
      UsersService.getUsers(true);
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

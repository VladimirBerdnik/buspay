<template>
  <v-dialog
    :value="visible"
    max-width="400"
    @input="close"
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex xs12>
        <v-card class="elevation-12">
          <v-toolbar dark>
            <v-toolbar-title>{{ $t('forms.user.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >

              <v-text-field
                v-validate="'required'"
                v-model="user.first_name"
                :error-messages="errors.collect('first_name')"
                :label="$t('user.fields.first_name')"
                :data-vv-as="$t('user.fields.first_name')"
                name="first_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required'"
                v-model="user.last_name"
                :error-messages="errors.collect('last_name')"
                :label="$t('user.fields.last_name')"
                :data-vv-as="$t('user.fields.last_name')"
                name="last_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required|email'"
                v-model="user.email"
                :error-messages="errors.collect('email')"
                :label="$t('user.fields.email')"
                :data-vv-as="$t('user.fields.email')"
                name="email"
                browser-autocomplete="off"
                type="text"
                required
              />
              <v-text-field
                v-validate="existingUser ? 'min:6' : 'required|min:6'"
                v-model="user.password"
                :append-icon="passwordHidden ? 'visibility' : 'visibility_off'"
                :type="passwordHidden ? 'password' : 'text'"
                :error-messages="errors.collect('password')"
                :label="$t(`forms.user.inputs.password.${existingUser ? 'optional' : 'required'}`)"
                :data-vv-as="$t('user.fields.password')"
                name="password"
                browser-autocomplete="off"
                data-vv-name="password"
                required
                @click:append="() => (passwordHidden = !passwordHidden)"
              />
              <RoleSelect v-validate="'required'"
                          v-model="user.role_id"
                          :error-messages="errors.collect('role_id')"
                          :data-vv-as="$t('user.fields.role.name')"
                          name="role_id"
              />
              <CompanySelect v-validate="companyRequired ? 'required' : ''"
                             v-show="companyRequired"
                             v-model="user.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('user.fields.company.name')"
                             :clearable="false"
                             name="company_id"
                             @input="validateField('company_id')"
              />
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-layout row
                      wrap
                      justify-end
            >
              <v-spacer/>
              <v-btn color="default"
                     @click="close"
              >
                {{ $t('common.buttons.close') }}
              </v-btn>
              <v-btn color="primary"
                     @click="save"
              >
                {{ $t('common.buttons.save') }}
              </v-btn>
            </v-layout>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-dialog>
</template>

<script>
import AlertsService from '../../services/AlertsService';
import UsersService from '../../services/UsersService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';

export default {
  name:       'UserForm',
  components: { RoleSelect, CompanySelect },
  mixins:     [
    FormValidationMixin,
    ModalFormMixin,
  ],
  props: {
    value: {
      type:    Object,
      default: () => {},
    },
  },
  data() {
    return {
      passwordHidden: true,
      user:           {
        id:         null,
        first_name: null,
        last_name:  null,
        password:   null,
        email:      null,
        role_id:    null,
        company_id: null,
      },
    };
  },
  computed: {
    existingUser() {
      return this.user.id;
    },
    companyRequired() {
      return this.user.role_id && UsersService.roleWithCompany(this.user.role_id);
    },
  },
  watch: {
    value(newValue) {
      this.user = Object.assign({}, newValue);
    },
    companyRequired(required) {
      if (!required) {
        this.user.company_id = null;
      }
    },
  },
  methods: {
    /**
     * Performs save request.
     */
    async save() {
      if (!await this.revalidateForm()) {
        return;
      }

      UsersService.save(this.user)
        .then(() => {
          AlertsService.info(this.$i18n.t('common.notifications.changesSaved'));
          this.$emit('saved');
          this.close();
        })
        .catch(error => {
          if (this.isValidationError(error)) {
            this.handleValidationError(error.response.data.errors);
          }
        });
    },
    /**
     * Closes modal window.
     */
    close() {
      this.passwordHidden = true;
      this.$emit('close', false);
    },
  },
};
</script>

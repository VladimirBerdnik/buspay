<template>
  <v-dialog
    :value="visible"
    max-width="400"
    persistent
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
                v-model="item.first_name"
                :error-messages="errors.collect('first_name')"
                :label="$t('user.fields.first_name')"
                :data-vv-as="$t('user.fields.first_name')"
                :readonly="!formEditable"
                name="first_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required'"
                v-model="item.last_name"
                :error-messages="errors.collect('last_name')"
                :label="$t('user.fields.last_name')"
                :data-vv-as="$t('user.fields.last_name')"
                :readonly="!formEditable"
                name="last_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required|email'"
                v-model="item.email"
                :error-messages="errors.collect('email')"
                :label="$t('user.fields.email')"
                :data-vv-as="$t('user.fields.email')"
                :readonly="!formEditable"
                name="email"
                browser-autocomplete="off"
                type="text"
                required
              />
              <v-text-field
                v-validate="itemExists ? 'min:6' : 'required|min:6'"
                v-model="item.password"
                :append-icon="passwordHidden ? 'visibility' : 'visibility_off'"
                :type="passwordHidden ? 'password' : 'text'"
                :error-messages="errors.collect('password')"
                :label="$t(`forms.user.inputs.password.${itemExists ? 'optional' : 'required'}`)"
                :data-vv-as="$t('user.fields.password')"
                :readonly="!formEditable"
                name="password"
                browser-autocomplete="off"
                data-vv-name="password"
                required
                @click:append="() => (passwordHidden = !passwordHidden)"
              />
              <RoleSelect v-validate="'required'"
                          v-model="item.role_id"
                          :error-messages="errors.collect('role_id')"
                          :data-vv-as="$t('user.fields.role.name')"
                          :clearable="false"
                          :fallback-item="item.role"
                          :readonly="!formEditable"
                          name="role_id"
              />
              <CompanySelect v-validate="companyRequired ? 'required' : ''"
                             v-show="companyRequired"
                             v-model="item.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('user.fields.company.name')"
                             :clearable="false"
                             :readonly="!formEditable"
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
              <v-btn v-if="formSubmittable"
                     :loading="inProgress"
                     color="primary"
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
import UsersService from '../../services/UsersService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import EntityFormMixin from '../../mixins/EntityFormMixin';

export default {
  name:       'UserForm',
  components: { RoleSelect, CompanySelect },
  mixins:     [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      passwordHidden: true,
      item:           {
        id:         null,
        first_name: null,
        last_name:  null,
        password:   null,
        email:      null,
        role_id:    null,
        company_id: null,
      },
      service: UsersService,
    };
  },
  computed: {
    companyRequired() {
      return this.item.role_id && UsersService.roleWithCompany(this.item.role_id);
    },
  },
  watch: {
    companyRequired(required) {
      if (!required) {
        this.item.company_id = null;
      }
    },
  },
  methods: {
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

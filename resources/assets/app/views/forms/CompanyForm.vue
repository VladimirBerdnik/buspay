<template>
  <v-dialog
    :value="visible"
    max-width="360"
    @input="close"
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex xs12>
        <v-card class="elevation-12">
          <v-toolbar dark>
            <v-toolbar-title>{{ $t('forms.company.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form @keyup.native.enter="save">

              <v-text-field
                v-validate="'required'"
                v-model="item.name"
                :error-messages="errors.collect('name')"
                :label="$t('company.fields.name')"
                :data-vv-as="$t('company.fields.name')"
                name="name"
                type="text"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="item.bin"
                :error-messages="errors.collect('bin')"
                :label="$t('company.fields.bin')"
                :data-vv-as="$t('company.fields.bin')"
                name="bin"
                type="text"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="item.account_number"
                :error-messages="errors.collect('account_number')"
                :label="$t('company.fields.account_number')"
                :data-vv-as="$t('company.fields.account_number')"
                name="account_number"
                type="text"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="item.contact_information"
                :error-messages="errors.collect('contact_information')"
                :label="$t('company.fields.contact_information')"
                :data-vv-as="$t('company.fields.contact_information')"
                name="contact_information"
                type="text"
                required
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
              <v-btn :loading="inProgress"
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
import CompaniesService from '../../services/CompaniesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import EntityFormMixin from '../../mixins/EntityFormMixin';

export default {
  name:   'CompanyForm',
  mixins: [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:                  null,
        name:                null,
        bin:                 null,
        account_number:      null,
        contact_information: null,
      },
      service: CompaniesService,
    };
  },
};
</script>

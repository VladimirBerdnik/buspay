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
                v-model="company.name"
                :error-messages="errors.collect('name')"
                :label="$t('company.fields.name')"
                :data-vv-as="$t('company.fields.name')"
                name="name"
                type="text"
                data-vv-name="name"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="company.bin"
                :error-messages="errors.collect('bin')"
                :label="$t('company.fields.bin')"
                :data-vv-as="$t('company.fields.bin')"
                name="bin"
                type="text"
                data-vv-name="bin"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="company.account_number"
                :error-messages="errors.collect('account_number')"
                :label="$t('company.fields.account_number')"
                :data-vv-as="$t('company.fields.account_number')"
                name="account_number"
                type="text"
                data-vv-name="account_number"
                required
              />

              <v-text-field
                v-validate="'required'"
                v-model="company.contact_information"
                :error-messages="errors.collect('contact_information')"
                :label="$t('company.fields.contact_information')"
                :data-vv-as="$t('company.fields.contact_information')"
                name="contact_information"
                type="text"
                data-vv-name="contact_information"
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
import CompaniesService from '../../services/CompaniesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';

export default {
  name:   'CompanyForm',
  mixins: [
    FormValidationMixin,
  ],
  props: {
    value: {
      type:    Object,
      default: () => {},
    },
    visible: {
      type:    Boolean,
      default: false,
    },
  },
  data() {
    return {
      company: {
        id:                  null,
        name:                null,
        bin:                 null,
        account_number:      null,
        contact_information: null,
      },
    };
  },
  watch: {
    value(newValue) {
      this.company = Object.assign({}, newValue);
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

      CompaniesService.saveCompany(this.company)
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
      this.$emit('close', false);
    },
  },
};
</script>

<style scoped>

</style>

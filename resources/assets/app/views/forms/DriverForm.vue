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
            <v-toolbar-title>{{ $t('forms.driver.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <v-text-field
                v-validate="'required'"
                v-model="driver.full_name"
                :error-messages="errors.collect('full_name')"
                :label="$t('driver.fields.full_name')"
                :data-vv-as="$t('driver.fields.full_name')"
                name="full_name"
                type="text"
                required
              />
              <CompanySelect v-validate="'required'"
                             v-model="driver.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('driver.fields.company.name')"
                             :clearable="false"
                             :readonly="!!driver.id"
                             name="company_id"
              />
              <BusSelect v-validate="''"
                         v-show="driver.company_id"
                         v-model="driver.bus_id"
                         :company-id="driver.company_id"
                         :error-messages="errors.collect('bus_id')"
                         :data-vv-as="$t('driver.fields.bus.name')"
                         name="bus_id"
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
import DriversService from '../../services/DriversService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import BusSelect from '../dropdowns/BusSelect';

export default {
  name:       'DriverForm',
  components: { BusSelect, RoleSelect, CompanySelect },
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
      driver: {
        id:         null,
        full_name:  null,
        company_id: null,
        bus_id:     null,
      },
    };
  },
  watch: {
    value(newValue) {
      this.driver = Object.assign({}, newValue);
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

      DriversService.save(this.driver)
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

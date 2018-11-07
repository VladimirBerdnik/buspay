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
            <v-toolbar-title>{{ $t('forms.bus.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <v-text-field
                v-validate="'required'"
                v-model="bus.model_name"
                :error-messages="errors.collect('model_name')"
                :label="$t('bus.fields.model_name')"
                :data-vv-as="$t('bus.fields.model_name')"
                name="model_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required'"
                v-model="bus.state_number"
                :error-messages="errors.collect('state_number')"
                :label="$t('bus.fields.state_number')"
                :data-vv-as="$t('bus.fields.state_number')"
                name="state_number"
                type="text"
                required
              />
              <CompanySelect v-validate="'required'"
                             v-model="bus.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('bus.fields.company.name')"
                             :clearable="false"
                             name="company_id"
              />
              <RouteSelect v-validate="''"
                           v-show="bus.company_id"
                           v-model="bus.route_id"
                           :company-id="bus.company_id"
                           :error-messages="errors.collect('route_id')"
                           :data-vv-as="$t('bus.fields.route.name')"
                           name="route_id"
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
import BusesService from '../../services/BusesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import RouteSelect from '../dropdowns/RouteSelect';

export default {
  name:       'BusForm',
  components: { RouteSelect, RoleSelect, CompanySelect },
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
      bus: {
        id:           null,
        model_name:   null,
        state_number: null,
        company_id:   null,
        route_id:     null,
      },
    };
  },
  watch: {
    value(newValue) {
      this.bus = Object.assign({}, newValue);
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

      BusesService.save(this.bus)
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

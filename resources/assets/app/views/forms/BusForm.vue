<template>
  <v-dialog
    :value="visible"
    max-width="360"
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
            <v-toolbar-title>{{ $t('forms.bus.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <v-text-field
                v-validate="'required'"
                v-model="item.model_name"
                :error-messages="errors.collect('model_name')"
                :label="$t('bus.fields.model_name')"
                :data-vv-as="$t('bus.fields.model_name')"
                name="model_name"
                type="text"
                required
              />
              <v-text-field
                v-validate="'required'"
                v-model="item.state_number"
                :error-messages="errors.collect('state_number')"
                :label="$t('bus.fields.state_number')"
                :data-vv-as="$t('bus.fields.state_number')"
                name="state_number"
                type="text"
                required
              />
              <CompanySelect v-validate="'required'"
                             v-model="item.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('bus.fields.company.name')"
                             :clearable="false"
                             name="company_id"
              />
              <RouteSelect v-validate="''"
                           v-show="item.company_id"
                           v-model="item.route_id"
                           :company-id="item.company_id"
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
import BusesService from '../../services/BusesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import RouteSelect from '../dropdowns/RouteSelect';
import EntityFormMixin from '../../mixins/EntityFormMixin';

export default {
  name:       'BusForm',
  components: { RouteSelect, RoleSelect, CompanySelect },
  mixins:     [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:           null,
        model_name:   null,
        state_number: null,
        company_id:   null,
        route_id:     null,
      },
      service: BusesService,
    };
  },
};
</script>

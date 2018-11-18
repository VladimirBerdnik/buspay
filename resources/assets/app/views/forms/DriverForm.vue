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
            <v-toolbar-title>{{ $t('forms.driver.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <v-text-field
                v-validate="'required'"
                v-model="item.full_name"
                :error-messages="errors.collect('full_name')"
                :label="$t('driver.fields.full_name')"
                :data-vv-as="$t('driver.fields.full_name')"
                name="full_name"
                type="text"
                required
              />
              <CompanySelect v-validate="'required'"
                             v-model="item.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('driver.fields.company.name')"
                             :clearable="false"
                             :readonly="!!item.id"
                             name="company_id"
              />
              <BusSelect v-validate="''"
                         v-show="item.company_id"
                         v-model="item.bus_id"
                         :company-id="item.company_id"
                         :error-messages="errors.collect('bus_id')"
                         :data-vv-as="$t('driver.fields.bus.state_number')"
                         name="bus_id"
              />
              <DriverCardSelect v-validate="''"
                                v-model="item.card_id"
                                :error-messages="errors.collect('card_id')"
                                :data-vv-as="$t('driver.fields.card.card_number')"
                                name="card_id"
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
import DriversService from '../../services/DriversService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import BusSelect from '../dropdowns/BusSelect';
import DriverCardSelect from '../dropdowns/DriverCardSelect';
import EntityFormMixin from '../../mixins/EntityFormMixin';

export default {
  name:       'DriverForm',
  components: {
    DriverCardSelect, BusSelect, CompanySelect,
  },
  mixins: [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:         null,
        full_name:  null,
        company_id: null,
        bus_id:     null,
      },
      service: DriversService,
    };
  },
};
</script>

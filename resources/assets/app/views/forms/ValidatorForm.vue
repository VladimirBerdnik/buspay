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
            <v-toolbar-title>{{ $t('forms.validator.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <v-text-field
                v-model="item.serial_number"
                :label="$t('validator.fields.serial_number')"
                name="serial_number"
                type="text"
                readonly
              />
              <v-text-field
                v-model="item.model"
                :label="$t('validator.fields.model')"
                name="model"
                type="text"
                readonly
              />
              <v-text-field
                v-model="item.external_id"
                :label="$t('validator.fields.external_id')"
                name="external_id"
                type="text"
                readonly
              />
              <BusSelect v-validate="''"
                         v-model="item.bus_id"
                         :error-messages="errors.collect('bus_id')"
                         :data-vv-as="$t('validator.fields.bus.state_number')"
                         :readonly="!formEditable"
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
import FormValidationMixin from '../../mixins/FormValidationMixin';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import BusSelect from '../dropdowns/BusSelect';
import EntityFormMixin from '../../mixins/EntityFormMixin';
import ValidatorsService from '../../services/ValidatorsService';
import PoliciesService from '../../services/PoliciesService';

export default {
  name:       'ValidatorForm',
  components: {
    BusSelect,
  },
  mixins: [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:            null,
        serial_number: null,
        model:         null,
        external_id:   null,
        bus_id:        null,
      },
      service:  ValidatorsService,
      itemType: PoliciesService.itemsTypes.validators,
    };
  },
};
</script>

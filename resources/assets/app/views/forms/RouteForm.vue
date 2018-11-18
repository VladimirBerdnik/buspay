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
            <v-toolbar-title>{{ $t('forms.route.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >

              <v-text-field
                v-validate="'required'"
                v-model="item.name"
                :error-messages="errors.collect('name')"
                :label="$t('route.fields.name')"
                :data-vv-as="$t('route.fields.name')"
                name="name"
                type="text"
                required
              />
              <CompanySelect v-validate="''"
                             v-model="item.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('route.fields.company.name')"
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
import RoutesService from '../../services/RoutesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import EntityFormMixin from '../../mixins/EntityFormMixin';

export default {
  name:       'RouteForm',
  components: { CompanySelect },
  mixins:     [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:         null,
        name:       null,
        company_id: null,
      },
      service: RoutesService,
    };
  },
};
</script>

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
            <v-toolbar-title>{{ $t('forms.route.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >

              <v-text-field
                v-validate="'required'"
                v-model="route.name"
                :error-messages="errors.collect('name')"
                :label="$t('route.fields.name')"
                :data-vv-as="$t('route.fields.name')"
                name="name"
                type="text"
                required
              />
              <CompanySelect v-validate="''"
                             v-model="route.company_id"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('route.fields.company.name')"
                             clearable
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
import RoutesService from '../../services/RoutesService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';

export default {
  name:       'RouteForm',
  components: { CompanySelect },
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
      route: {
        id:         null,
        name:       null,
        company_id: null,
      },
    };
  },
  watch: {
    value(newValue) {
      this.route = Object.assign({}, newValue);
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

      RoutesService.save(this.route)
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

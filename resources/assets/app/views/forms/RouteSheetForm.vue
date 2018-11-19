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
            <v-toolbar-title>{{ $t('forms.routeSheet.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form autocomplete="off"
                    @keyup.native.enter="save"
            >
              <CompanySelect v-validate="'required'"
                             v-model="item.company_id"
                             :readonly="itemExists"
                             :error-messages="errors.collect('company_id')"
                             :data-vv-as="$t('routeSheet.fields.company.name')"
                             :clearable="false"
                             name="company_id"
              />
              <RouteSelect v-validate="''"
                           v-model="item.route_id"
                           :company-id="item.company_id"
                           :error-messages="errors.collect('route_id')"
                           :data-vv-as="$t('routeSheet.fields.route.name')"
                           name="route_id"
              />
              <BusSelect v-validate="'required'"
                         v-model="item.bus_id"
                         :company-id="item.company_id"
                         :error-messages="errors.collect('bus_id')"
                         :data-vv-as="$t('routeSheet.fields.bus.state_number')"
                         name="bus_id"
              />
              <DriverSelect v-validate="''"
                            v-model="item.driver_id"
                            :company-id="item.company_id"
                            :error-messages="errors.collect('driver_id')"
                            :data-vv-as="$t('routeSheet.fields.driver.full_name')"
                            name="driver_id"
              />
              <v-layout row>
                <v-flex xs8>
                  <DateSelect v-validate="'required'"
                              v-model="item.active_from"
                              :label="$t('routeSheet.fields.active_from')"
                              :error-messages="errors.collect('active_from')"
                              :data-vv-as="$t('routeSheet.fields.active_from')"
                              name="active_from"
                  />
                </v-flex>
                <v-flex xs4
                        class="pl-2"
                >
                  <TimeSelect v-validate="'required'"
                              v-model="item.active_from"
                              :readonly="!Boolean(item.active_from)"
                              :error-messages="errors.collect('active_from')"
                              :data-vv-as="$t('routeSheet.fields.active_from')"
                              name="active_from_time"
                  />
                </v-flex>
              </v-layout>
              <v-layout row>
                <v-flex xs8>
                  <DateSelect v-validate="''"
                              v-model="item.active_to"
                              :label="$t('routeSheet.fields.active_to')"
                              :error-messages="errors.collect('active_to')"
                              :data-vv-as="$t('routeSheet.fields.active_to')"
                              :clearable="true"
                              name="active_to"
                  />
                </v-flex>
                <v-flex xs4
                        class="pl-2"
                >
                  <TimeSelect v-validate="''"
                              v-model="item.active_to"
                              :readonly="!Boolean(item.active_to)"
                              :error-messages="errors.collect('active_to')"
                              :data-vv-as="$t('routeSheet.fields.active_to')"
                              name="active_to_time"
                  />
                </v-flex>
              </v-layout>
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
import RouteSheetsService from '../../services/RouteSheetsService';
import FormValidationMixin from '../../mixins/FormValidationMixin';
import CompanySelect from '../dropdowns/CompanySelect';
import RoleSelect from '../dropdowns/RoleSelect';
import ModalFormMixin from '../../mixins/ModalFormMixin';
import RouteSelect from '../dropdowns/RouteSelect';
import EntityFormMixin from '../../mixins/EntityFormMixin';
import BusSelect from '../dropdowns/BusSelect';
import DriverSelect from '../dropdowns/DriverSelect';
import DateSelect from '../dropdowns/DateSelect';
import TimeSelect from '../dropdowns/TimeSelect';

export default {
  name:       'RouteSheetForm',
  components: {
    TimeSelect,
    DateSelect,
    DriverSelect,
    BusSelect,
    RouteSelect,
    RoleSelect,
    CompanySelect,
  },
  mixins: [
    ModalFormMixin,
    FormValidationMixin,
    EntityFormMixin,
  ],
  data() {
    return {
      item: {
        id:          null,
        company_id:  null,
        route_id:    null,
        bus_id:      null,
        driver_id:   null,
        active_from: null,
        active_to:   null,
      },
      service: RouteSheetsService,
    };
  },
  watch: {
    value: {
      deep: true,
      handler(newValue) {
        this.item = Object.assign({}, newValue);
      },
    },
  },
};
</script>

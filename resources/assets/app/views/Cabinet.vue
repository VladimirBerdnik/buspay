<template>
  <div v-if="authenticated">
    <the-splash-screen v-if="!dataReady"
                       :steps="steps"
    />
    <v-navigation-drawer :mini-variant="mini"
                         :clipped="true"
                         :value:="true"
                         permanent
                         dark
                         app
    >
      <v-list class="pa-0"
              dense
      >
        <v-list-tile>
          <v-spacer v-if="!mini"/>
          <v-list-tile-action>
            <v-btn
              icon
              @click.stop="mini = !mini"
            >
              <v-icon x-large>{{ mini ? 'chevron_right' : 'chevron_left' }}</v-icon>
            </v-btn>
          </v-list-tile-action>
        </v-list-tile>
      </v-list>

      <v-list dense>
        <template v-for="(menuItem, entityType) in menuItems">
          <v-list-tile v-if="policies.listRetrievingAllowed(entityType)"
                       :key="entityType"
                       :to="menuItem.to"
                       :title="$t(`layout.drawer.${entityType}`)"
                       class="py-2"
          >
            <v-list-tile-action class="my-2">
              <v-icon v-if="menuItem.icon"
                      x-large
              >
                {{ menuItem.icon }}
              </v-icon>
            </v-list-tile-action>
            <v-list-tile-content class="my-2">
              <v-list-tile-title class="subheading">
                {{ $t(`layout.drawer.${entityType}`) }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
      </v-list>
    </v-navigation-drawer>
    <router-view/>
  </div>
</template>

<script>
import AuthService from '../services/AuthService';
import i18n from '../lang/i18n';
import * as routes from '../router';
import entitiesTypes from '../policies/entitiesTypes';
import CompaniesService from '../services/CompaniesService';
import RolesService from '../services/RolesService';
import CardTypesService from '../services/CardTypesService';
import UsersService from '../services/UsersService';
import RoutesService from '../services/RoutesService';
import BusesService from '../services/BusesService';
import DriversService from '../services/DriversService';
import DriversCardsService from '../services/DriversCardsService';
import ValidatorsService from '../services/ValidatorsService';
import AlertsService from '../services/AlertsService';
import TheSplashScreen from '../components/TheSplashScreen';

const cabinetPreparationSteps = {
  [entitiesTypes.roles]:        { text: i18n.t('layout.drawer.roles'), ready: false, service: RolesService },
  [entitiesTypes.driversCards]: { text: i18n.t('layout.drawer.driversCards'), ready: false, service: DriversCardsService },
  [entitiesTypes.companies]:    { text: i18n.t('layout.drawer.companies'), ready: false, service: CompaniesService },
  [entitiesTypes.users]:        { text: i18n.t('layout.drawer.users'), ready: false, service: UsersService },
  [entitiesTypes.routes]:       { text: i18n.t('layout.drawer.routes'), ready: false, service: RoutesService },
  [entitiesTypes.buses]:        { text: i18n.t('layout.drawer.buses'), ready: false, service: BusesService },
  [entitiesTypes.drivers]:      { text: i18n.t('layout.drawer.drivers'), ready: false, service: DriversService },
  [entitiesTypes.validators]:   { text: i18n.t('layout.drawer.validators'), ready: false, service: ValidatorsService },
  [entitiesTypes.cardTypes]:    { text: i18n.t('layout.drawer.cardTypes'), ready: false, service: CardTypesService },
};

export default {
  name:       'Cabinet',
  components: { TheSplashScreen },
  data:       () => ({
    mini:      true,
    menuItems: {
      [entitiesTypes.companies]:   { icon: 'business', to: { name: routes.ROUTE_COMPANIES } },
      [entitiesTypes.users]:       { icon: 'supervisor_account', to: { name: routes.ROUTE_USERS } },
      [entitiesTypes.routes]:      { icon: 'map', to: { name: routes.ROUTE_ROUTES } },
      [entitiesTypes.buses]:       { icon: 'directions_bus', to: { name: routes.ROUTE_BUSES } },
      [entitiesTypes.drivers]:     { icon: 'recent_actors', to: { name: routes.ROUTE_DRIVERS } },
      [entitiesTypes.validators]:  { icon: 'nfc', to: { name: routes.ROUTE_VALIDATORS } },
      [entitiesTypes.tariffs]:     { icon: 'attach_money', to: { name: routes.ROUTE_TARIFFS } },
      [entitiesTypes.cardTypes]:   { icon: 'style', to: { name: routes.ROUTE_CARD_TYPES } },
      [entitiesTypes.cards]:       { icon: 'credit_card', to: { name: routes.ROUTE_CARDS } },
      [entitiesTypes.routeSheets]: { icon: 'today', to: { name: routes.ROUTE_ROUTE_SHEETS } },
    },
    steps: {},
  }),
  computed: {
    /**
     * Whether user authenticated or not.
     *
     * @return {boolean}
     */
    authenticated: () => AuthService.isAuthenticated(),

    /**
     * Whether data for personal cabinet ready or not.
     */
    dataReady() {
      return Object.values(this.steps).every(step => step.ready);
    },
  },
  watch: {
    /**
     * Watch for user authentication status.
     */
    authenticated() {
      this.validateAuth();
    },
  },
  async mounted() {
    if (!this.validateAuth()) {
      return;
    }
    Object.keys(cabinetPreparationSteps).forEach(async step => {
      if (!this.policies.listRetrievingAllowed(step)) {
        return;
      }
      this.$set(this.steps, step, cabinetPreparationSteps[step]);
      await this.steps[step].service.read().then(() => { this.steps[step].ready = true; });
    });
  },
  methods: {
    /**
     * Check user authentication and redirect to homepage when user is not authenticated.
     *
     * @return {boolean}
     */
    validateAuth() {
      if (!this.authenticated) {
        AlertsService.warning('Выполните вход, пожалуйста');
        this.$router.push({ name: routes.ROUTE_HOME });
      }

      return this.authenticated;
    },
  },
};
</script>

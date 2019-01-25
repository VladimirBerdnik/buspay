<template>
  <div v-if="cabinetReady">
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
        <template v-for="(menuItem, itemType) in menuItems">
          <v-list-tile v-if="policies.canSeeList(itemType)"
                       :key="itemType"
                       :to="menuItem.to"
                       :title="$t(`layout.drawer.${itemType}`)"
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
                {{ $t(`layout.drawer.${itemType}`) }}
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
import itemsTypes from '../policies/itemsTypes';
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
import PoliciesService from '../services/PoliciesService';
import ProfileService from '../services/ProfileService';

const cabinetPreparationSteps = {
  [itemsTypes.roles]:        { text: i18n.t('layout.drawer.role'), ready: false, service: RolesService },
  [itemsTypes.driversCards]: { text: i18n.t('layout.drawer.driverCard'), ready: false, service: DriversCardsService },
  [itemsTypes.companies]:    { text: i18n.t('layout.drawer.company'), ready: false, service: CompaniesService },
  [itemsTypes.users]:        { text: i18n.t('layout.drawer.user'), ready: false, service: UsersService },
  [itemsTypes.routes]:       { text: i18n.t('layout.drawer.route'), ready: false, service: RoutesService },
  [itemsTypes.buses]:        { text: i18n.t('layout.drawer.bus'), ready: false, service: BusesService },
  [itemsTypes.drivers]:      { text: i18n.t('layout.drawer.driver'), ready: false, service: DriversService },
  [itemsTypes.validators]:   { text: i18n.t('layout.drawer.validator'), ready: false, service: ValidatorsService },
  [itemsTypes.cardTypes]:    { text: i18n.t('layout.drawer.cardType'), ready: false, service: CardTypesService },
};

export default {
  name:       'Cabinet',
  components: { TheSplashScreen },
  data:       () => ({
    mini:      true,
    menuItems: {
      [itemsTypes.companies]:      { icon: 'business', to: { name: routes.ROUTE_COMPANIES } },
      [itemsTypes.users]:          { icon: 'supervisor_account', to: { name: routes.ROUTE_USERS } },
      [itemsTypes.routes]:         { icon: 'map', to: { name: routes.ROUTE_ROUTES } },
      [itemsTypes.buses]:          { icon: 'directions_bus', to: { name: routes.ROUTE_BUSES } },
      [itemsTypes.drivers]:        { icon: 'recent_actors', to: { name: routes.ROUTE_DRIVERS } },
      [itemsTypes.validators]:     { icon: 'nfc', to: { name: routes.ROUTE_VALIDATORS } },
      [itemsTypes.tariffs]:        { icon: 'attach_money', to: { name: routes.ROUTE_TARIFFS } },
      [itemsTypes.cardTypes]:      { icon: 'style', to: { name: routes.ROUTE_CARD_TYPES } },
      [itemsTypes.cards]:          { icon: 'credit_card', to: { name: routes.ROUTE_CARDS } },
      [itemsTypes.routeSheets]:    { icon: 'today', to: { name: routes.ROUTE_ROUTE_SHEETS } },
      [itemsTypes.replenishments]: { icon: 'assignment_returned', to: { name: routes.ROUTE_REPLENISHMENTS } },
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
     * Determines whether all requred data are ready to init personal cabinet or not.
     *
     * @return {boolean}
     */
    cabinetReady: () => AuthService.isAuthenticated()
      && PoliciesService.policies
      && ProfileService.get(),

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
  mounted() {
    if (!this.validateAuth()) {
      return;
    }
    this.initCabinet();
  },
  methods: {
    /**
     * Initializes cabinet required details retrieving.
     */
    async initCabinet() {
      await ProfileService.read();
      await PoliciesService.read();
      this.$nextTick(() => {
        Object.keys(cabinetPreparationSteps).forEach(async step => {
        // Workaround for drivers card entity pseudo type
          if (step === itemsTypes.driversCards) {
          // Drivers cards entity type list retrieving should be checked by cards intention
            if (!this.policies.can(itemsTypes.cards, this.policies.intentions.getDriversCards)) {
              return;
            }
          } else if (!this.policies.canSeeList(step)) {
            return;
          }
          this.$set(this.steps, step, cabinetPreparationSteps[step]);
          await this.steps[step].service.read().then(() => {
            this.steps[step].ready = true;
          });
        });
      });
    },
    /**
     * Check user authentication and redirect to homepage when user is not authenticated.
     *
     * @return {boolean}
     */
    validateAuth() {
      if (!this.authenticated) {
        // TODO lang resource
        AlertsService.warning('Выполните вход, пожалуйста');
        this.$router.push({ name: routes.ROUTE_HOME });
      }

      return this.authenticated;
    },
  },
};
</script>

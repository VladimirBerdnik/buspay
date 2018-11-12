<template>
  <div v-if="authenticated">
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
        <v-list-tile v-for="menuItem in menuItems"
                     :key="menuItem.text"
                     :to="menuItem.to"
                     :title="menuItem.text"
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
            <v-list-tile-title class="subheading">{{ menuItem.text }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <router-view/>
  </div>
</template>

<script>
import AuthService from '../services/AuthService';
import i18n from '../lang/i18n';
import * as routes from '../router';
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

export default {
  name: 'Cabinet',
  data: () => ({
    mini:      true,
    menuItems: [
      { icon: 'business', text: i18n.t('layout.drawer.companies'), to: { name: routes.ROUTE_COMPANIES } },
      { icon: 'supervisor_account', text: i18n.t('layout.drawer.users'), to: { name: routes.ROUTE_USERS } },
      { icon: 'map', text: i18n.t('layout.drawer.routes'), to: { name: routes.ROUTE_ROUTES } },
      { icon: 'directions_bus', text: i18n.t('layout.drawer.buses'), to: { name: routes.ROUTE_BUSES } },
      { icon: 'recent_actors', text: i18n.t('layout.drawer.drivers'), to: { name: routes.ROUTE_DRIVERS } },
      { icon: 'nfc', text: i18n.t('layout.drawer.validators'), to: { name: routes.ROUTE_VALIDATORS } },
      { icon: 'attach_money', text: i18n.t('layout.drawer.tariffs'), to: { name: routes.ROUTE_TARIFFS } },
      { icon: 'style', text: i18n.t('layout.drawer.cardTypes'), to: { name: routes.ROUTE_CARD_TYPES } },
      { icon: 'credit_card', text: i18n.t('layout.drawer.cards'), to: { name: routes.ROUTE_CARDS } },
      { icon: 'today', text: i18n.t('layout.drawer.routeSheets') },
    ],
  }),
  computed: {
    /**
     * Whether user authenticated or not.
     *
     * @return {boolean}
     */
    authenticated: () => AuthService.isAuthenticated(),
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

    RolesService.read();
    CardTypesService.read();
    CompaniesService.read();
    RoutesService.read();
    BusesService.read();
    DriversCardsService.read();
    UsersService.read();
    DriversService.read();
    ValidatorsService.read();
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

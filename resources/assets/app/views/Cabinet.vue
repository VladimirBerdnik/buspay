<template>
  <div v-if="authenticated()">
    <v-navigation-drawer
      :clipped="$vuetify.breakpoint.mdAndUp"
      :value:="true"
      app
    >
      <v-list dense>
        <v-list-tile v-for="menuItem in menuItems"
                     :key="menuItem.text"
                     :to="menuItem.to"
                     class="py-2"
        >
          <v-list-tile-action class="my-2">
            <v-icon v-if="menuItem.icon"
                    x-large>{{ menuItem.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content class="my-2">
            <v-list-tile-title class="title">{{ menuItem.text }}</v-list-tile-title>
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

export default {
  name: 'Cabinet',
  data: () => ({
    menuItems: [
      { icon: 'business', text: i18n.t('layout.drawer.companies') },
      { icon: 'supervisor_account', text: i18n.t('layout.drawer.users') },
      { icon: 'map', text: i18n.t('layout.drawer.routes') },
      { icon: 'directions_bus', text: i18n.t('layout.drawer.buses') },
      { icon: 'recent_actors', text: i18n.t('layout.drawer.drivers') },
      { icon: 'nfc', text: i18n.t('layout.drawer.validators') },
      { icon: 'today', text: i18n.t('layout.drawer.routeSheets') },
      { icon: 'attach_money', text: i18n.t('layout.drawer.tariffs') },
      { icon: 'style', text: i18n.t('layout.drawer.cardTypes'), to: { name: routes.ROUTE_CARD_TYPES } },
      { icon: 'credit_card', text: i18n.t('layout.drawer.cards') },
    ],
  }),
  methods: {
    authenticated: () => AuthService.isAuthenticated(),
  },
};
</script>

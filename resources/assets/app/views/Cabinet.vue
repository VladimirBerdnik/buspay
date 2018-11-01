<template>
  <div v-if="profile">
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
  </div>
</template>

<script>
import ProfileService from '../services/ProfileService';
import i18n from '../lang/i18n';

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
      { icon: 'style', text: i18n.t('layout.drawer.cardTypes'), to: { name: '' } },
      { icon: 'credit_card', text: i18n.t('layout.drawer.cards') },
    ],
  }),
  computed: {
    /**
     * Profile details getter.
     *
     * @returns {User}
     */
    profile: () => ProfileService.getProfile(),
  },
  async mounted() {
    /**
     * Retrieves profile information.
     */
    await ProfileService.readProfile();
  },
};
</script>

<template>
  <div v-show="alerts.length > 0"
       class="alerts-container">
    <v-container>
      <v-layout row>
        <v-flex xs12>
          <v-alert
            v-for="(alert, index) in alerts.slice(0, 3)"
            :key="index"
            :value="true"
            :type="alert.type"
            transition="slide-y-reverse-transition"
            class="custom-alert"
          >
            <b>{{ getAlertMessage(alert) }}</b>
          </v-alert>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
import AlertsService from '../services/AlertsService';

export default {
  name:     'TheGlobalAlerts',
  computed: {
    alerts: () => AlertsService.getAlerts(),
  },
  methods: {
    getAlertMessage(alert) {
      // Called from markup to retrieve alert text and register alert disappearing.
      if (!alert.disappearing) {
        setTimeout(() => {
          AlertsService.removeAlert(alert);
        }, 5000);
      }

      // eslint-disable-next-line no-unused-expressions,no-param-reassign
      alert.disappearing = true;

      return alert.message;
    },
  },
};
</script>

<style scoped>
.alerts-container {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 201;
}

.custom-alert {
  font-size: 18px;
  z-index: 999;
}
</style>

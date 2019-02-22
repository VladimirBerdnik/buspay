<template>
  <div v-show="alerts.length > 0"
       class="alerts-container"
  >
    <v-alert
      v-for="(alert, index) in alerts.slice(0, 3)"
      :key="index"
      :value="true"
      :type="alert.type"
      transition="slide-y-reverse-transition"
      class="custom-alert"
      dismissible
    >
      <b>{{ getAlertMessage(alert) }}</b>
    </v-alert>
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
  width: 80%;
  left: 10%;
  z-index: 203;
}

.custom-alert {
  font-size: 18px;
  z-index: 999;
}
</style>

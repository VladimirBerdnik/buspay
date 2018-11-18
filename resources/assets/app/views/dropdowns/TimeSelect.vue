<template>
  <v-menu
    ref="menu"
    :close-on-content-click="false"
    v-model="menu"
    :nudge-right="40"
    :return-value.sync="time"
    lazy
    transition="scale-transition"
    offset-y
    full-width
    max-width="290px"
    min-width="290px"
  >
    <v-text-field
      slot="activator"
      v-model="time"
      :label="label"
      append-icon="access_time"
      readonly
    />
    <v-time-picker
      v-if="menu"
      v-model="time"
      :readonly="readonly"
      format="24hr"
      full-width
      @change="$refs.menu.save(time)"
    />
  </v-menu>
</template>

<script>
import moment from 'moment';
import dateUtils from '../../utils/date';

export default {
  name:  'TimeSelect',
  props: {
    label: {
      type:    String,
      default: null,
    },
    value: {
      type:    String,
      default: null,
    },
    readonly: {
      type:    Boolean,
      default: false,
    },
  },
  data: () => ({
    time: null,
    menu: false,
  }),
  watch: {
    value(newValue) {
      this.time = dateUtils.formatDate(newValue, dateUtils.formats.shortTime, null);
    },
    time(newTime) {
      let value = newTime;

      if (newTime) {
        const [ hours, minutes ] = newTime.split(':');

        value = moment(this.value)
          .minutes(minutes)
          .hour(hours)
          .second(0)
          .format();
      }
      this.$emit('input', value);
    },
  },
};
</script>

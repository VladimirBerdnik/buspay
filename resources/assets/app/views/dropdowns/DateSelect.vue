<template>
  <v-menu
    ref="menu"
    :close-on-content-click="false"
    v-model="menu"
    :nudge-right="40"
    lazy
    transition="scale-transition"
    offset-y
    full-width
    max-width="290px"
    min-width="290px"
  >
    <v-text-field
      slot="activator"
      v-model="dateFormatted"
      :label="label"
      :error-messages="errorMessages"
      :clearable="clearable"
      persistent-hint
      append-icon="event"
      @input="parseInput"
    />
    <v-date-picker v-model="date"
                   no-title
                   locale="ru-Ru"
                   first-day-of-week="1"
                   @input="menu = false"
    />
  </v-menu>
</template>

<script>
import moment from 'moment';
import dateUtils from '../../utils/date';

export default {
  name:  'DateSelect',
  props: {
    label: {
      type:    String,
      default: null,
    },
    value: {
      type:    String,
      default: null,
    },
    errorMessages: {
      type:    Array,
      default: () => [],
    },
    clearable: {
      type:    Boolean,
      default: true,
    },
  },
  data: () => ({
    date:          null,
    dateFormatted: null,
    menu:          false,
    hours:         0,
    minutes:       0,
  }),
  watch: {
    value(newValue) {
      if (!newValue) {
        this.date = null;
        this.hours = 0;
        this.minutes = 0;

        return;
      }
      this.date = dateUtils.formatDate(newValue, dateUtils.formats.isoDateTime);

      const momentDate = moment(newValue);

      this.minutes = momentDate.minutes();
      this.hours = momentDate.hours();
    },
    date(newValue) {
      this.dateFormatted = this.formatDate(newValue);

      let date = newValue;

      if (date) {
        const momentDate = moment(newValue);

        date = momentDate.hours(this.hours).minutes(this.minutes).seconds(0).toISOString(false);
        this.date = date;
      }

      this.$emit('input', date);
    },
  },
  methods: {
    formatDate(date) {
      return dateUtils.formatDate(date, dateUtils.formats.shortDate, null);
    },
    parseInput() {
      if (!this.dateFormatted) {
        this.date = null;
      }
    },
  },
};
</script>

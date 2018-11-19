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
import FormFieldMixin from '../../mixins/FormFieldMixin';

export default {
  name:   'DateSelect',
  mixins: [FormFieldMixin],
  data:   () => ({
    date:          null,
    dateFormatted: null,
    menu:          false,
    hours:         0,
    minutes:       0,
  }),
  watch: {
    /**
     * When component model is changed need to remember time of passed date and use value as component value to edit.
     *
     * @param {Date} newValue New date passed from outside
     */
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
    /**
     * When date in component is changed need to notify parent about new value with respect to passed time.
     *
     * @param {String} newValue New value to notify parent
     */
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
    /**
     * Formats date as user readable string.
     *
     * @param {Date|String} date Date to format
     *
     * @return {string}
     */
    formatDate(date) {
      return dateUtils.formatDate(date, dateUtils.formats.shortDate, null);
    },
    /**
     * Parses user input of date.
     */
    parseInput() {
      if (!this.dateFormatted) {
        this.date = null;
      }
    },
  },
};
</script>

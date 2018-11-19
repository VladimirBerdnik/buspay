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
import FormFieldMixin from '../../mixins/FormFieldMixin';

export default {
  name:   'TimeSelect',
  mixins: [FormFieldMixin],
  data:   () => ({
    time: null,
    menu: false,
  }),
  watch: {
    /**
     * When component model is changed need to use value as component value to edit.
     *
     * @param {Date} newValue New date passed from outside
     */
    value(newValue) {
      this.time = dateUtils.formatDate(newValue, dateUtils.formats.shortTime, null);
    },
    /**
     * When time in component is changed need to notify parent about new value.
     *
     * @param {String} newValue New value to notify parent
     */
    time(newValue) {
      let value = newValue;

      if (newValue) {
        const [ hours, minutes ] = newValue.split(':');

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

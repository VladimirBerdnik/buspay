import Vue from 'vue';
import moment from 'moment';
import { SECONDS_PER_DAY, MILLISECONDS_PER_SECOND } from './timeConstants';
import i18n from '../lang/i18n';
import dateUtils from './date';

// Register date filters with format
Object.entries(dateUtils.formats).forEach(([ filter, format ]) => {
  Vue.filter(filter, (date, wrongDateResult = '') =>
    dateUtils.formatDate(date, format, wrongDateResult));
});

Vue.filter('fromNow', date => moment(date).fromNow());

Vue.filter('currency', floatNumber =>
  floatNumber.toLocaleString('ru-Ru', { maximumFractionDigits: 2 }));

Vue.filter('secondsToTime', seconds => {
  const secondsTotal = parseInt(seconds, 10);
  let days = Math.floor(secondsTotal / SECONDS_PER_DAY);
  const time = moment(secondsTotal * MILLISECONDS_PER_SECOND)
    .utc()
    .format('HH:mm:ss');

  if (!days) {
    days = '';
  } else {
    const daysLabel = i18n.t('common.daysLabel');

    days = `${days}${daysLabel}`;
  }

  return `${days} ${time}`.trim();
});

Vue.filter('nl2br', (str, isXhtml) => {
  const breakTag =
    isXhtml || typeof isXhtml === 'undefined' ? '<br />' : '<br>';

  return String(str).replace(
    /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
    `$1${breakTag}$2`,
  );
});

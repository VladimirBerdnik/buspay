import Vue from 'vue';
import moment from 'moment';
import { SECONDS_PER_DAY, MILLISECONDS_PER_SECOND } from './timeConstants';
import i18n from '../lang/i18n';

/**
 * Helper function to format given date by given format.
 *
 * @param {Date} date Date to format
 * @param {String} format Format
 * @param {String} wrongDateResult When date is empty then this message will be returned
 * @return {string}
 */
function formatDate(date, format, wrongDateResult = '') {
  if (date) {
    return moment(date).format(format);
  }

  return wrongDateResult;
}

// Vue date filters
const dateFilters = {
  monthDay:     'M/D',
  timeStamp:    'MM/DD/YYYY h:mm A',
  shortDate:    'M/D/YY',
  fullDate:     'MMMM D, YYYY',
  shortIsoDate: 'YYYY-MM-DD',
};

// Register date filters with format
Object.entries(dateFilters).forEach(([ filter, format ]) => {
  Vue.filter(filter, (date, wrongDateResult = '') =>
    formatDate(date, format, wrongDateResult));
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

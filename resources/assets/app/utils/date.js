import moment from 'moment';

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
    try {
      return moment(date).format(format);
    } catch (e) {
      // no action need
    }
  }

  return wrongDateResult;
}

// Application date formats
const formats = {
  monthDay:      'M.D',
  timeStamp:     'DD.MM.YYYY HH:mm',
  fullTimeStamp: 'DD.MM.YYYY HH:mm:ss',
  shortDate:     'DD.MM.YYYY',
  shortTime:     'HH:mm',
  fullDate:      'D MMMM YYYY',
  shortIsoDate:  'YYYY-MM-DD',
  isoDateTime:   'YYYY-MM-DD HH:mm:ss',
};

export default {
  formats, formatDate,
};

import moment from 'moment';

/**
 * This object contains default values of app.
 */
export default {
  activeFrom: moment().startOf('month').toISOString(),
};

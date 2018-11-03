import axios from 'axios';
import AuthService from '../services/AuthService';
import AlertsService from '../services/AlertsService';
import i18n from '../lang/i18n';
import config from '../config';
import * as utils from '../utils/utils';

const http = axios.create({
  baseURL: config.api.url,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add auth token for every request when presented
http.interceptors.request.use(
  config => {
    if (AuthService.isAuthenticated()) {
      config.headers.Authorization = `Bearer ${AuthService.getToken()}`;
    }

    return config;
  },
  error => Promise.reject(error),
);

/**
 * Retrieves error message from error HTTP response.
 *
 * @param {*} error Error response object
 * @param {string} defaultMessage Fallback message
 *
 * @return {string}
 */
function getMessageFromResponse(error, defaultMessage) {
  if (error && error.response) {
    if (error.response.data && error.response.data.message) {
      return error.response.data.message;
    }

    return error.response.statusText;
  }

  return defaultMessage;
}

// Error handler interceptor
http.interceptors.response.use(
  response => response,
  error => {
    // React on server errors
    if (utils.hasServerError(error.response)) {
      const errorMessage = getMessageFromResponse(error, i18n.t('common.notifications.serverError'));

      AlertsService.error(errorMessage);
    }

    // React on client errors
    if (utils.hasClientError(error.response)) {
      const errorMessage = getMessageFromResponse(error, i18n.t('common.notifications.clientError'));

      AlertsService.error(errorMessage);
    }

    // React on network error
    if (!error.response) {
      const errorMessage = getMessageFromResponse(error, i18n.t('common.notifications.networkError'));

      AlertsService.error(errorMessage);
    }

    return Promise.reject(error);
  }
);

export default http;

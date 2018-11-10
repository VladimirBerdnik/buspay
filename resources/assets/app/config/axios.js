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
    if (utils.hasServerError(error.response)) {
      // React on server errors
      AlertsService.error(getMessageFromResponse(error, i18n.t('common.notifications.serverError')));
    } else if (utils.hasUnauthenticatedError(error.response)) {
      // Do not react to unauthenticated error like "token mismatch" or "token not provided"
    } else if (utils.hasClientError(error.response)) {
      // React on client error
      AlertsService.error(getMessageFromResponse(error, i18n.t('common.notifications.clientError')));
    } else if (!error.response) {
      // React on network error
      AlertsService.error(getMessageFromResponse(error, i18n.t('common.notifications.networkError')));
    }

    return Promise.reject(error);
  }
);

export default http;

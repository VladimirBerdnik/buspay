import axios from 'axios';
import AuthService from '../services/AuthService';
import AlertsService from '../services/AlertsService';
import i18n from '../lang/i18n';
import config from '../config';
import * as utils from '../utils/utils';
import UserInteractionService from '../services/UserInteractionService';

const http = axios.create({
  baseURL: config.api.url,
  headers: {
    'Content-Type': 'application/json',
  },
});

/**
 * Returns formatted bearer authentication token.
 *
 * @return {string}
 */
function getBearerToken() {
  return `Bearer ${AuthService.getToken()}`;
}

// Add auth token for every request when presented
http.interceptors.request.use(
  config => {
    if (AuthService.isAuthenticated()) {
      config.headers.Authorization = getBearerToken();
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
  async error => {
    const originalRequest = error.config;

    if (utils.hasServerError(error.response)) {
      // React on server errors
      AlertsService.error(getMessageFromResponse(error, i18n.t('common.notifications.serverError')));
    } else if (utils.hasUnauthenticatedError(error.response)) {
      const oldTokenUsed = originalRequest.headers.Authorization !== getBearerToken();

      if (originalRequest.retried) {
        return Promise.reject(error);
      }

      originalRequest.retried = true;

      if (!oldTokenUsed) {
        try {
          if (utils.hasTokenExpiredError(error.response)) {
            await AuthService.refreshToken();
          } else {
            await UserInteractionService.handleLogin();
          }
        } catch (e) {
          AuthService.logoutMutation();
        }
      }

      originalRequest.headers.Authorization = getBearerToken();
      originalRequest.url = originalRequest.url.replace(config.api.url, '');

      return http(originalRequest).then(response => response);
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

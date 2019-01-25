export const TOKEN_GETTER = 'tokenGetter';
export const AUTHENTICATED_GETTER = 'authenticatedGetter';
export const LOGIN_MODAL_GETTER = 'loginModal';
export const CONFIRM_MODAL_GETTER = 'confirmModal';
export const ERROR_NOTIFICATION_MODAL_GETTER = 'errorNotificationModal';
export const ALERTS_GETTER = 'alerts';
export const PROFILE_GETTER = 'profile';
export const CARD_TYPES_GETTER = 'cardTypes';
export const TARIFF_PERIODS_GETTER = 'tariffPeriods';
export const TARIFFS_GETTER = 'tariffs';
export const COMPANIES_GETTER = 'companies';
export const USERS_GETTER = 'users';
export const ROLES_GETTER = 'roles';
export const ROUTES_GETTER = 'routes';
export const BUSES_GETTER = 'buses';
export const DRIVERS_CARDS_GETTER = 'driversCards';
export const VALIDATORS_GETTER = 'validators';
export const DRIVERS_GETTER = 'drivers';
export const CARDS_GETTER = 'cards';
export const CARDS_PAGINATION_GETTER = 'cardsPagination';
export const ROUTE_SHEETS_GETTER = 'routeSheets';
export const ROUTE_SHEETS_PAGINATION_GETTER = 'routeSheetsPagination';
export const REPLENISHMENTS_GETTER = 'replenishments';
export const REPLENISHMENTS_PAGINATION_GETTER = 'replenishmentsPagination';

const getters = {
  /**
   * Returns authentication token.
   *
   * @param {Object} state - Vuex state.
   *
   * @return {string|null}
   */
  [TOKEN_GETTER]: state => state.auth.token,

  /**
   * Returns true if user is authenticated.
   *
   * @param {Object} state - Vuex state.
   *
   * @return {boolean}
   */
  [AUTHENTICATED_GETTER]: state => state.auth.authenticated,

  /**
   * Returns authenticated user details.
   *
   * @param {Object} state - Vuex state.
   *
   * @return {User}
   */
  [PROFILE_GETTER]: state => state.auth.user,
};

/**
 * List of simple getters that returns same value from storage as it's names.
 *
 * @type {string[]}
 */
const simpleGetters = [
  LOGIN_MODAL_GETTER,
  CONFIRM_MODAL_GETTER,
  ERROR_NOTIFICATION_MODAL_GETTER,
  ALERTS_GETTER,
  CARD_TYPES_GETTER,
  TARIFF_PERIODS_GETTER,
  TARIFFS_GETTER,
  COMPANIES_GETTER,
  USERS_GETTER,
  ROLES_GETTER,
  ROUTES_GETTER,
  BUSES_GETTER,
  DRIVERS_GETTER,
  CARDS_GETTER,
  DRIVERS_CARDS_GETTER,
  CARDS_PAGINATION_GETTER,
  VALIDATORS_GETTER,
  ROUTE_SHEETS_GETTER,
  ROUTE_SHEETS_PAGINATION_GETTER,
  REPLENISHMENTS_GETTER,
  REPLENISHMENTS_PAGINATION_GETTER,
];

simpleGetters.forEach(getterType => {
  getters[getterType] = state => state[getterType];
});

export default getters;

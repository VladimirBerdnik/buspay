/**
 * Checks that response has server error.
 *
 * @param {Object} response Response to check
 * @return {Boolean} Checking result
 */
export function hasServerError(response) {
  return response && response.status >= 500 && response.status <= 511;
}

/**
 * Checks that response has client error.
 *
 * @param {Object} response Response to check
 * @return {Boolean} Checking result
 */
export function hasClientError(response) {
  return response && response.status >= 400 && response.status < 500;
}

/**
 * Checks that response has unauthenticated error.
 *
 * @param {Object} response Response to check
 * @return {Boolean} Checking result
 */
export function hasUnauthenticatedError(response) {
  return response && response.status === 401;
}

/**
 * Checks that response has token expired error.
 *
 * @param {Object} response Response to check
 * @return {Boolean} Checking result
 */
export function hasTokenExpiredError(response) {
  return response && hasUnauthenticatedError(response) && response.data.code === 498;
}

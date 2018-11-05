/**
 * Person who can log in into application and perform some actions.
 *
 * @typedef {Object} User
 *
 * @property {number} id - User identifier.
 * @property {string} first_name - First name.
 * @property {string} last_name - Last name.
 * @property {string} email - Main email address.
 * @property {Role|null} role - User role in application.
 * @property {Company|null} company - In which company user works.
 */

/**
 * Used to determine tariff amount for card or to recognize bus driver's card.
 *
 * @typedef {Object} CardType
 * @property {number} id - Card type identifier.
 * @property {string} name - Name of card type.
 */

/**
 * User role in application. Determines access to different application features
 *
 * @typedef {Object} Role
 * @property {number} id - Role identifier.
 * @property {string} name - Name of role.
 */

/**
 * Determines tariff fares price for tariffs and card types in different periods of time.
 *
 * @typedef {Object} TariffPeriod
 * @property {number} id - Tariff period identifier.
 * @property {string} active_from - Start date of tariff fare activity period.
 * @property {string} active_to - End date of tariff fare activity period.
 */

/**
 * Stores information about tariff fare price on tariff for card type on tariff activity period.
 *
 * @typedef {Object} TariffFare
 * @property {number} id - Tariff fare identifier.
 * @property {number} tariff_id - Tariff identifier, for which this fare applicable.
 * @property {number} card_type_id - Card type identifier, for which this fare applicable.
 * @property {number} tariff_period_id - Activity period identifier, for which this fare applicable.
 * @property {number} amount - Tariff fare amount (price).
 */

/**
 * Determines tariff that has different fares for different card types in different periods of time.
 *
 * @typedef {Object} Tariff
 * @property {number} id - Tariff identifier.
 * @property {string} name - Tariff name.
 * @property {TariffFare[]|null} tariffFares - Applicable for tariff fares.
 */

/**
 * Determines bus route that transport company serves by it's buses.
 *
 * @typedef {Object} Route
 * @property {number} id - Route identifier.
 * @property {string} name - Route name.
 * @property {Company|null} company - Transport company that is now assigned to route.
 */

/**
 * Transport company with buses that can serve routes. Company users have access to application.
 *
 * @typedef {Object} Company
 * @property {number} id - Company identifier.
 * @property {string} name - Company name.
 * @property {string} bin - Business identification number.
 * @property {string} account_number - Account number for payments.
 * @property {string} contact_information - Company contact information.
 * @property {number|null} buses_count - Company's buses count.
 * @property {number|null} drivers_count - Company's drivers count.
 * @property {number|null} routes_count - Company's routes count.
 * @property {number|null} users_count - Company's users count.
 */

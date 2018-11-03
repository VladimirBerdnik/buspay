/**
 * Person who can log in into application and perform some actions.
 *
 * @typedef {Object} User
 *
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
 * @property {Number} id - Card type identifier.
 * @property {String} name - Name of card type.
 */

/**
 * User role in application. Determines access to different application features
 *
 * @typedef {Object} Role
 * @property {Number} id - Role identifier.
 * @property {String} name - Name of role.
 */

/**
 * Determines tariff fares price for tariffs and card types in different periods of time.
 *
 * @typedef {Object} TariffPeriod
 * @property {Number} id - Tariff period identifier.
 * @property {String} active_from - Start date of tariff fare activity period.
 * @property {String} active_to - End date of tariff fare activity period.
 */

/**
 * Stores information about tariff fare price on tariff for card type on tariff activity period.
 *
 * @typedef {Object} TariffFare
 * @property {Number} id - Tariff fare identifier.
 * @property {Number} tariff_id - Tariff identifier, for which this fare applicable.
 * @property {Number} card_type_id - Card type identifier, for which this fare applicable.
 * @property {Number} tariff_period_id - Activity period identifier, for which this fare applicable.
 * @property {Number} amount - Tariff fare amount (price).
 */

/**
 * Determines tariff that has different fares for different card types in different periods of time.
 *
 * @typedef {Object} Tariff
 * @property {Number} id - Tariff identifier.
 * @property {string} name - Tariff name.
 * @property {TariffFare[]|null} tariff_fares - Applicable for tariff fares.
 */

/**
 * Transport company with buses that can serve routes. Company users have access to application.
 *
 * @typedef {Object} Company
 * @property {Number} id - Company identifier.
 * @property {string} name - Company name.
 * @property {string} bin - Business identification number.
 * @property {string} account_number - Account number for payments.
 * @property {string} contact_information - Company contact information.
 */

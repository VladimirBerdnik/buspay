/**
 * User. Person who can log in into application and perform some actions
 *
 * @typedef {Object} User - User entity.
 * @property {string} first_name - First name.
 * @property {string} last_name - Last name.
 * @property {string} email - Main email address.
 */

/**
 * Card type. Used to determine tariff amount for card or to recognize bus driver's card.
 *
 * @typedef {Object} CardType - Authentication card type.
 * @property {Number} id - Card type identifier.
 * @property {String} name - Name of card type.
 */

/**
 * Tariff period. Determines tariff fares price for tariffs and card types in different periods of time.
 *
 * @typedef {Object} TariffPeriod - Period of tariff fares activity.
 * @property {Number} id - Tariff period identifier.
 * @property {String} active_from - Start date of tariff fare activity period.
 * @property {String} active_to - End date of tariff fare activity period.
 */

/**
 * Tariff fare. Stores information about tariff fare price on tariff for card type on tariff activity period.
 *
 * @typedef {Object} TariffFare - Tariff fare amount for card type and tariff in tariff period.
 * @property {Number} id - Tariff fare identifier.
 * @property {Number} tariff_id - Tariff identifier, for which this fare applicable.
 * @property {Number} card_type_id - Card type identifier, for which this fare applicable.
 * @property {Number} tariff_period_id - Tariff activity period identifier, for which this fare applicable.
 * @property {Number} amount - Tariff fare amount (price).
 */

/**
 * Tariff. Determines tariff that has different fares for different card types in different periods of time.
 *
 * @typedef {Object} Tariff - Bus tariff. Applicable for different locations tariffs
 * @property {Number} id - Tariff identifier.
 * @property {string} name - Tariff name.
 * @property {TariffFare[]|null} tariff_fares - Applicable for tariff fares.
 */

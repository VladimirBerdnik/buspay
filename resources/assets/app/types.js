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
 * @property {number|null} cards_count - Count of cards with this type.
 */

/**
 * User role in application. Determines access to different application features.
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
 * @property {number|null} company_id - Company identifier that serves this route.
 * @property {number|null} buses_count - Assigned to route buses count.
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

/**
 * Bus that belongs to transport company and serves bus routes. Has assigned validators
 * and drivers that are rides this bus.
 *
 * @typedef {Object} Bus
 * @property {number} id - Bus identifier.
 * @property {string} model_name - Bus model name.
 * @property {string} state_number - Bus state number.
 * @property {number} company_id - Company identifier to which this bus belongs to.
 * @property {number|null} route_id - Route identifier to which this bus assigned by default.
 * @property {number|null} drivers_count - Assigned to bus drivers count.
 * @property {number|null} validators_count - Assigned to bus validators count.
 * @property {Company|null} company - Transport company to which this bus belongs to.
 * @property {Route|null} route - Route that this bus serves by default.
 */

/**
 * Driver that can drive buses. Works in transport companies.
 *
 * @typedef {Object} Driver
 * @property {number} id - Driver identifier.
 * @property {string} full_name - Drivers full name.
 * @property {number} company_id - Transport company identifier in which this driver works.
 * @property {number|null} bus_id - Bus identifier on which this driver works by default.
 * @property {number|null} card_id - Driver's authentication card identifier.
 * @property {Company|null} company - Transport company in which this driver works.
 * @property {Bus|null} bus - Bus on which this driver works by default.
 * @property {Card|null} card - Driver's authentication card.
 */

/**
 * Authentication cards that can be recognized by validators.
 *
 * @typedef {Object} Card
 * @property {number} id - Card identifier.
 * @property {number} card_type_id - Card type identifier.
 * @property {string} uin - Unique card number, patched to ROM.
 * @property {string} card_number - Short card number, written on card case.
 * @property {CardType} cardType - Card type.
 */

/**
 * Smart device that can authorize payment cards in bus.
 *
 * @typedef {Object} Validator
 * @property {number} id - Validator identifier.
 * @property {string} serial_number - Validator serial number.
 * @property {string} model - Validator manufacturer or model.
 * @property {number|null} bus_id - Bus identifier where this validator now installed.
 * @property {Bus|null} bus - Bus where this validator now installed.
 */

/**
 * Route sheet represents bus with driver on route presence.
 *
 * @typedef {Object} RouteSheet
 * @property {number} id Route sheet unique identifier.
 * @property {number} company_id Company identifier to which this route sheet belongs to.
 * @property {number|null} route_id Bus route identifier, which served the driver on the bus.
 * @property {number} bus_id Bus identifier that is on route.
 * @property {number|null} driver_id Driver identifier that is on bus on route.
 * @property {string} active_from Start date of activity period of this record.
 * @property {string} active_to End date of activity period of this record.
 * @property {Company} company_id Company to which this route sheet belongs to.
 * @property {Route|null} route_id Bus route, which served the driver on the bus.
 * @property {Bus} bus_id Bus that is on route.
 * @property {Driver|null} driver_id Driver that is on bus on route.
 */

/**
 * Paginated list details.
 *
 * @typedef {Object} PaginationInfo
 * @property {number} total - How many items exists.
 * @property {number} count - How many items in currently loaded page.
 * @property {number} per_page - How many items per page was requested.
 * @property {number} current_page - Currently loaded page number.
 * @property {number} total_pages - How many pages exists.
 */

/**
 * Card balance transaction represents replenishment or write-off of balance amount at some date.
 *
 * @typedef {Object} CardBalanceTransactionData
 * @property {number} amount Transaction amount.
 * @property {string} date When transaction was performed.
 * @property {string} type Type of transaction.
 */

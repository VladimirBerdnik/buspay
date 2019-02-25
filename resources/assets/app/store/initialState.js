export default function getInitialState() {
  return {
    // Auth related data
    auth: {
      user:          null,
      token:         null,
      decodedToken:  null,
      authenticated: false,
    },
    // Modal windows data
    loginModal: {
      visible: false,
      params:  {},
    },
    confirmModal: {
      visible: false,
      params:  {
        message: '',
        title:   '',
      },
    },
    errorNotificationModal: {
      visible: false,
      params:  {},
    },
    // Alerts
    alerts:          [],
    // Data lists
    cardTypes:       [],
    tariffPeriods:   [],
    tariffs:         [],
    tariffFares:     [],
    companies:       [],
    users:           [],
    roles:           [],
    routes:          [],
    buses:           [],
    drivers:         [],
    driversCards:    [],
    validators:      [],
    cards:           [],
    cardsPagination: {
      total:        null,
      count:        null,
      per_page:     null,
      current_page: null,
      total_pages:  null,
    },
    routeSheets:           [],
    routeSheetsPagination: {
      total:        null,
      count:        null,
      per_page:     null,
      current_page: null,
      total_pages:  null,
    },
    replenishments:           [],
    replenishmentsPagination: {
      total:        null,
      count:        null,
      per_page:     null,
      current_page: null,
      total_pages:  null,
    },
    transactions:           [],
    transactionsPagination: {
      total:        null,
      count:        null,
      per_page:     null,
      current_page: null,
      total_pages:  null,
    },
  };
}

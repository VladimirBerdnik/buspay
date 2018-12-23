import itemsTypes from './itemsTypes';
import roles from './roles';
import intentions from './intentions';

/**
 * List of allowed for roles actions. Every entity has it's own list of possible actions.
 * Administrator can do everything that's why he is not presented in list of roles for intentions.
 */
export default {
  [itemsTypes.buses]: {
    [intentions.get]:            [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.show]:           [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.create]:         [roles.SUPPORT],
    [intentions.update]:         [roles.SUPPORT],
    [intentions.changeBusRoute]: [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.delete]:         [],
  },
  [itemsTypes.cardTypes]: {
    [intentions.get]: [roles.SUPPORT],
  },
  [itemsTypes.cards]: {
    [intentions.get]: [roles.SUPPORT],
  },
  [itemsTypes.companies]: {
    [intentions.get]:    [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.show]:   [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.drivers]: {
    [intentions.get]:             [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.show]:            [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.create]:          [roles.SUPPORT],
    [intentions.update]:          [roles.SUPPORT],
    [intentions.changeDriverBus]: [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.delete]:          [],
  },
  [itemsTypes.driversCards]: {
    [intentions.get]: [roles.SUPPORT],
  },
  [itemsTypes.profile]: {
    [intentions.show]:   [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.update]: [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
  },
  [itemsTypes.roles]: {
    [intentions.get]: [roles.SUPPORT],
  },
  [itemsTypes.routeSheets]: {
    [intentions.get]:    [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.show]:   [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.create]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.update]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.delete]: [roles.TRANSPORT_COMPANY_OPERATOR],
  },
  [itemsTypes.routes]: {
    [intentions.get]:    [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.show]:   [ roles.TRANSPORT_COMPANY_OPERATOR, roles.SUPPORT ],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.tariffs]: {
    [intentions.get]:    [roles.SUPPORT],
    [intentions.show]:   [roles.SUPPORT],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.tariffsPeriods]: {
    [intentions.get]:    [roles.SUPPORT],
    [intentions.show]:   [roles.SUPPORT],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.users]: {
    [intentions.get]:    [roles.SUPPORT],
    [intentions.show]:   [roles.SUPPORT],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.validators]: {
    [intentions.get]:    [roles.SUPPORT],
    [intentions.show]:   [roles.SUPPORT],
    [intentions.update]: [roles.SUPPORT],
  },
};

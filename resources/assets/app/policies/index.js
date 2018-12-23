import itemsTypes from './itemsTypes';
import roles from './roles';
import intentions from './intentions';

/**
 * List of allowed for roles actions. Every entity has it's own list of possible actions.
 * Administrator can do everything that's why he is not presented in list of roles for intentions.
 */
export default {
  [itemsTypes.buses]: {
    [intentions.get]:            [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.show]:           [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.changeBusRoute]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.create]:         [],
    [intentions.update]:         [],
    [intentions.delete]:         [],
  },
  [itemsTypes.cardTypes]: {
    [intentions.get]: [],
  },
  [itemsTypes.cards]: {
    [intentions.get]: [],
  },
  [itemsTypes.companies]: {
    [intentions.get]:    [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.show]:   [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.drivers]: {
    [intentions.get]:             [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.show]:            [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.create]:          [],
    [intentions.update]:          [],
    [intentions.changeDriverBus]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.delete]:          [],
  },
  [itemsTypes.driversCards]: {
    [intentions.get]: [],
  },
  [itemsTypes.profile]: {
    [intentions.show]:   [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.update]: [roles.TRANSPORT_COMPANY_OPERATOR],
  },
  [itemsTypes.roles]: {
    [intentions.get]: [],
  },
  [itemsTypes.routeSheets]: {
    [intentions.get]:    [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.show]:   [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.create]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.update]: [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.delete]: [roles.TRANSPORT_COMPANY_OPERATOR],
  },
  [itemsTypes.routes]: {
    [intentions.get]:    [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.show]:   [roles.TRANSPORT_COMPANY_OPERATOR],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.tariffs]: {
    [intentions.get]:    [],
    [intentions.show]:   [],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.tariffsPeriods]: {
    [intentions.get]:    [],
    [intentions.show]:   [],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.users]: {
    [intentions.get]:    [],
    [intentions.show]:   [],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
  [itemsTypes.validators]: {
    [intentions.get]:    [],
    [intentions.show]:   [],
    [intentions.create]: [],
    [intentions.update]: [],
    [intentions.delete]: [],
  },
};

/**
 * Contains list of user intentions that can be applied to application entities.
 */
export default {

  // General intentions

  /** Read single item details */
  show:   'show',
  /** Read list of items */
  get:    'get',
  /** Create new item */
  create: 'create',
  /** Update existing item */
  update: 'update',
  /** Delete existing item */
  delete: 'delete',

  // Entity specific intentions

  /** Update assigned to bus default route */
  changeBusRoute:  'changeBusRoute',
  /** Update assigned to driver default bus */
  changeDriverBus: 'changeDriverBus',
};

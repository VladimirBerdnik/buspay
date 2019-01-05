import roles from '../policies/roles';
import intentions from '../policies/intentions';
import itemsTypes from '../policies/itemsTypes';
import ProfileService from './ProfileService';
import axios from '../config/axios';
/**
 * Service to check ability of user to perform some intention on entities.
 */
export default {
  itemsTypes,
  intentions,

  policies: {},

  /**
   * Reads policies configuration.
   *
   * @return {[]}
   *
   * @throws Error
   */
  async read() {
    const response = await axios.get('/policies/');

    this.policies = (response.data || {});

    return response.data;
  },

  /**
   * Returns whether requested action is allowed for currently logged in user or not.
   *
   * @param {string} itemType Entity type for which intention need to check
   * @param {string} intention Desired intention to perform
   *
   * @return {boolean}
   */
  can(itemType, intention) {
    const user = ProfileService.get();

    // Admin can do anything
    if (user.role.id === roles.ADMIN) {
      return true;
    }

    return (
      user
      && user.role
      && user.role.id
      // policies declared
      && this.policies
      // policies for entity declared
      && this.policies[itemType]
      // policies for intention for entity declared
      && this.policies[itemType][intention]
      // role is in list of allowed intention executors
      && this.policies[itemType][intention].indexOf(user.role.id) !== -1
    );
  },
  /**
   * Returns whether show intention is allowed for given entity type or not.
   *
   * @param {string} itemType Entity type to check intention for
   *
   * @return {boolean}
   */
  canSeeItem(itemType) {
    return this.can(itemType, intentions.show);
  },
  /**
   * Returns whether get intention is allowed for given entity type or not.
   *
   * @param {string} itemType Entity type to check intention for
   *
   * @return {boolean}
   */
  canSeeList(itemType) {
    return this.can(itemType, intentions.get);
  },
  /**
   * Returns whether create intention is allowed for given entity type or not.
   *
   * @param {string} itemType Entity type to check intention for
   *
   * @return {boolean}
   */
  canCreate(itemType) {
    return this.can(itemType, intentions.create);
  },
  /**
   * Returns whether update intention is allowed for given entity type or not.
   *
   * @param {string} itemType Entity type to check intention for
   *
   * @return {boolean}
   */
  canUpdate(itemType) {
    return this.can(itemType, intentions.update);
  },
  /**
   * Returns whether delete intention is allowed for given entity type or not.
   *
   * @param {string} itemType Entity type to check intention for
   *
   * @return {boolean}
   */
  canDelete(itemType) {
    return this.can(itemType, intentions.delete);
  },
};

import roles from '../policies/roles';
import policies from '../policies';
import intentions from '../policies/intentions';
import ProfileService from './ProfileService';

/**
 * Service to check ability of user to perform some intention on entities.
 */
export default {
  /**
   * Returns whether requested action is allowed for currently logged in user or not.
   *
   * @param {string} entityType Entity type for which intention need to check
   * @param {string} intention Desired intention to perform
   *
   * @return {boolean}
   */
  actionAllowed(entityType, intention) {
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
      && policies
      // policies for entity declared
      && policies[entityType]
      // policies for intention for entity declared
      && policies[entityType][intention]
      // role is in list of allowed intention executors
      && policies[entityType][intention].indexOf(user.role.id) !== -1
    );
  },
  /**
   * Returns whether show intention is allowed for given entity type or not.
   *
   * @param {string} entityType Entity type to check intention for
   *
   * @return {boolean}
   */
  seeingAllowed(entityType) {
    return this.actionAllowed(entityType, intentions.show);
  },
  /**
   * Returns whether get intention is allowed for given entity type or not.
   *
   * @param {string} entityType Entity type to check intention for
   *
   * @return {boolean}
   */
  listRetrievingAllowed(entityType) {
    return this.actionAllowed(entityType, intentions.get);
  },
  /**
   * Returns whether create intention is allowed for given entity type or not.
   *
   * @param {string} entityType Entity type to check intention for
   *
   * @return {boolean}
   */
  creationAllowed(entityType) {
    return this.actionAllowed(entityType, intentions.create);
  },
  /**
   * Returns whether update intention is allowed for given entity type or not.
   *
   * @param {string} entityType Entity type to check intention for
   *
   * @return {boolean}
   */
  updatingAllowed(entityType) {
    return this.actionAllowed(entityType, intentions.update);
  },
  /**
   * Returns whether delete intention is allowed for given entity type or not.
   *
   * @param {string} entityType Entity type to check intention for
   *
   * @return {boolean}
   */
  deletionAllowed(entityType) {
    return this.actionAllowed(entityType, intentions.delete);
  },
};

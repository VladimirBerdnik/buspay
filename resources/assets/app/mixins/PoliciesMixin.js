import PoliciesService from '../services/PoliciesService';
/**
 * Mixin to check ability of user to perform some intention on entities.
 */
export default {
  data() {
    return {
      policies: PoliciesService,
    };
  },
};

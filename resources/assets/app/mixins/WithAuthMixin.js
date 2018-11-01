import UserInteractionService from '../services/UserInteractionService';
import AuthService from '../services/AuthService';

export default {
  methods: {
    withAuth(resolve, reject) {
      if (AuthService.isAuthenticated()) {
        resolve();
      } else {
        UserInteractionService.handleLogin().then(resolve, reject);
      }
    },
  },
};

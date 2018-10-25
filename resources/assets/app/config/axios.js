import axios from 'axios';
import authService from '../services/authService';
import config from '../config';

const http = axios.create({
  baseURL: config.api.url,
  headers: {
    'Content-Type': 'application/json',
  },
});

http.interceptors.request.use(
  config => {
    if (authService.isAuthenticated()) {
      config.headers.Authorization = `Bearer ${authService.getToken()}`;
    }

    return config;
  },
  error => Promise.reject(error),
);

export default http;

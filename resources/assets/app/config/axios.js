import axios from 'axios';
import AuthService from '../services/AuthService';
import config from '../config';

const http = axios.create({
  baseURL: config.api.url,
  headers: {
    'Content-Type': 'application/json',
  },
});

http.interceptors.request.use(
  config => {
    if (AuthService.isAuthenticated()) {
      config.headers.Authorization = `Bearer ${AuthService.getToken()}`;
    }

    return config;
  },
  error => Promise.reject(error),
);

export default http;

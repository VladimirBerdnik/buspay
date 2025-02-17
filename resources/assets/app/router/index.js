import Vue from 'vue';
import Router from 'vue-router';
import Home from '../views/Home';
import Cabinet from '../views/Cabinet';
import CardTypes from '../views/tables/CardTypes';
import TariffFares from '../views/tables/TariffFares';
import Companies from '../views/tables/Companies';
import Users from '../views/tables/Users';
import Routes from '../views/tables/Routes';
import Buses from '../views/tables/Buses';
import Drivers from '../views/tables/Drivers';
import Cards from '../views/tables/Cards';
import Validators from '../views/tables/Validators';
import RouteSheets from '../views/tables/RouteSheets';
import Replenishments from '../views/tables/Replenishments';
import CardDetails from '../views/CardDetails';
import Transactions from '../views/tables/Transactions';
import GeneralReport from '../views/tables/GeneralReport';

export const ROUTE_HOME = 'home';
export const ROUTE_CABINET = 'cabinet';
export const ROUTE_CARD_TYPES = 'cardTypes';
export const ROUTE_TARIFF_FARES = 'tariffs';
export const ROUTE_COMPANIES = 'companies';
export const ROUTE_USERS = 'users';
export const ROUTE_ROUTES = 'routes';
export const ROUTE_BUSES = 'buses';
export const ROUTE_DRIVERS = 'drivers';
export const ROUTE_CARDS = 'cards';
export const ROUTE_VALIDATORS = 'validators';
export const ROUTE_ROUTE_SHEETS = 'routeSheets';
export const ROUTE_CARD_DETAILS = 'cardDetails';
export const ROUTE_REPLENISHMENTS = 'replenishments';
export const ROUTE_TRANSACTIONS = 'transactions';
export const ROUTE_GENERAL_REPORT = 'generalReport';

Vue.use(Router);

export default new Router({
  mode:     'history',
  fallback: false,
  routes:   [ {
    path:      '/',
    component: Home,
    name:      ROUTE_HOME,
  }, {
    path:      '/cabinet',
    component: Cabinet,
    name:      ROUTE_CABINET,
    children:  [
      { path: 'cardTypes', component: CardTypes, name: ROUTE_CARD_TYPES },
      { path: 'tariffs', component: TariffFares, name: ROUTE_TARIFF_FARES },
      { path: 'companies', component: Companies, name: ROUTE_COMPANIES },
      { path: 'users', component: Users, name: ROUTE_USERS },
      { path: 'routes', component: Routes, name: ROUTE_ROUTES },
      { path: 'buses', component: Buses, name: ROUTE_BUSES },
      { path: 'drivers', component: Drivers, name: ROUTE_DRIVERS },
      { path: 'cards', component: Cards, name: ROUTE_CARDS },
      { path: 'validators', component: Validators, name: ROUTE_VALIDATORS },
      { path: 'routeSheets', component: RouteSheets, name: ROUTE_ROUTE_SHEETS },
      { path: 'replenishments', component: Replenishments, name: ROUTE_REPLENISHMENTS },
      { path: 'transactions', component: Transactions, name: ROUTE_TRANSACTIONS },
      { path: 'reports/general', component: GeneralReport, name: ROUTE_GENERAL_REPORT },
    ],
  }, {
    path:      '/info/:cardNumber',
    component: CardDetails,
    name:      ROUTE_CARD_DETAILS,
  } ],
});

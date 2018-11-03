export default {
  app: {
    title: 'BusPay',
  },
  forms: {
    login: {
      title:  'Вход',
      inputs: {
        password: {
          label: 'Введите Ваш Пароль',
          name:  'Пароль',
        },
        email: {
          label: 'Введите Ваш Email',
          name:  'Email',
        },
      },
      buttons: {
        login: 'Войти',
        close: 'Отмена',
      },
    },
  },
  layout: {
    toolbar: {
      menu: {
        login:   'Войти',
        logout:  'Выход',
        cabinet: 'Панель управления',
        card:    {
          placeholder: 'Введите номер карты чтобы увидеть детализацию...',
        },
      },
    },
    drawer: {
      companies:   'Компании',
      users:       'Пользователи',
      routes:      'Маршруты',
      buses:       'Автобусы',
      drivers:     'Водители',
      validators:  'Валидаторы',
      routeSheets: 'Маршрутные листы',
      tariffs:     'Тарифы',
      cardTypes:   'Типы карт',
      cards:       'Карты',
    },
  },
  tables: {
    noResults: 'Нет результатов для выбранных параметров',
  },
  periods: {
    toNow: 'По текущее время',
  },
  cardType: {
    name:   'Тип карты',
    fields: {
      id:   'ID',
      name: 'Название',
    },
  },
  company: {
    name:   'Компания',
    fields: {
      id:                  'ID',
      name:                'Название',
      bin:                 'БИН',
      account_number:      'Номер счета',
      contact_information: 'Контактная информация',
      buses_count:         'Автобусов',
      drivers_count:       'Водителей',
      routes_count:        'Маршрутов',
    },
  },
  tariffPeriod: {
    name:   'Период действия тарифа',
    fields: {
      id:          'ID',
      active_from: 'Действует с',
      active_to:   'Действует по',
    },
  },
  tariff: {
    name:   'Тариф',
    fields: {
      id:   'ID',
      name: 'Название',
    },
  },
};

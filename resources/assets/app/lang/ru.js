import roles from '../enums/roles';

export default {
  app: {
    title: 'AutoToleu',
  },
  common: {
    buttons: {
      save:    'Сохранить',
      ok:      'ОК',
      close:   'Отмена',
      add:     'Добавить',
      delete:  'Удалить',
      refresh: 'Обновить',
    },
    notifications: {
      savingError:   'Ошибка сохранения',
      deletionError: 'Ошибка удаления',
      changesSaved:  'Изменения сохранены',
      recordDeleted: 'Запись удалена',
      serverError:   'Непредвиденная ошибка на сервере',
      clientError:   'Ошибка запроса',
      networkError:  'Непредвиденная ошибка, проверьте соединение с сетью',
    },
    titles: {
      confirmAction: 'Подтвердите действие',
      preparingData: 'Загружаются справочники',
    },
    placeholders: {
      search: 'Поиск',
    },
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
      },
    },
    company: {
      title: 'Параметры компании',
    },
    route: {
      title: 'Параметры маршрута',
    },
    bus: {
      title: 'Параметры автобуса',
    },
    driver: {
      title: 'Параметры водителя',
    },
    validator: {
      title: 'Параметры валидатора',
    },
    routeSheet: {
      title: 'Параметры маршуртного листа',
    },
    user: {
      title:  'Параметры пользователя',
      inputs: {
        password: {
          required: 'Пароль',
          optional: 'Пароль (пустой - оставить прежним)',
        },
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
  periods: {
    toNow: 'По текущее время',
  },
  cardType: {
    name:   'Тип карты',
    fields: {
      id:          'ID',
      name:        'Название',
      cards_count: 'Карт',
    },
  },
  company: {
    name:          'Компания',
    deleteConfirm: 'Удалить компанию "{item}"?',
    fields:        {
      id:                  'ID',
      name:                'Название',
      bin:                 'БИН',
      account_number:      'Номер счета',
      contact_information: 'Контактная информация',
      buses_count:         'Автобусов',
      drivers_count:       'Водителей',
      routes_count:        'Маршрутов',
      users_count:         'Пользователей',
    },
  },
  user: {
    name:          'Пользователь',
    deleteConfirm: 'Удалить пользователя "{item}"?',
    fields:        {
      id:         'ID',
      first_name: 'Имя',
      last_name:  'Фамилия',
      email:      'Email',
      password:   'Пароль',
      role:       {
        name: 'Роль',
      },
      company: {
        name: 'Компания',
      },
    },
  },
  driver: {
    name:          'Водитель',
    deleteConfirm: 'Удалить водителя "{item}"?',
    fields:        {
      id:        'ID',
      full_name: 'ФИО',
      company:   {
        name: 'Компания',
      },
      bus: {
        state_number: 'Автобус',
      },
      card: {
        card_number: 'Карта',
      },
    },
  },
  routeSheet: {
    name:   'Маршуртный лист',
    fields: {
      id:      'ID',
      company: {
        name: 'Компания',
      },
      bus: {
        state_number: 'Автобус',
      },
      driver: {
        full_name: 'Водитель',
      },
      route: {
        name: 'Маршрут',
      },
      active_from: 'Действует с',
      active_to:   'Действует по',
    },
  },
  route: {
    name:          'Маршрут',
    deleteConfirm: 'Удалить маршрут "{item}"?',
    fields:        {
      id:      'ID',
      name:    'Название',
      company: {
        name: 'Компания',
      },
      buses_count: 'Автобусов',
    },
  },
  bus: {
    name:          'Автобус',
    deleteConfirm: 'Удалить автобус "{item}"?',
    fields:        {
      id:           'ID',
      state_number: 'Гос.номер',
      model_name:   'Модель',
      company:      {
        name: 'Компания',
      },
      route: {
        name: 'Маршрут',
      },
      drivers_count:    'Водителей',
      validators_count: 'Валидаторов',
    },
  },
  role: {
    name:   'Роль',
    fields: {
      id:   'ID',
      name: 'Название',
    },
    items: {
      [roles.ADMIN]:      'Администратор',
      [roles.SUPPORT]:    'Тех.поддержка',
      [roles.OPERATOR]:   'Оператор',
      [roles.GOVERNMENT]: 'Гос.служащщий',
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
  card: {
    name:   'Карта',
    fields: {
      id:              'ID',
      card_number:     'Номер карты',
      card_type_id:    'Тип карты',
      uin:             'UIN',
      active:          'Активна',
      synchronized_at: 'Дата синхронизации',
      cardType:        {
        name: 'Тип карты',
      },
    },
  },
  validator: {
    name:   'Валидатор',
    fields: {
      id:            'ID',
      serial_number: 'Серийный номер',
      model:         'Модель',
      bus:           {
        state_number: 'Автобус',
      },
    },
  },
};

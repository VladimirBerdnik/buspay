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
};

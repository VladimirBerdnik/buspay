<?php

use App\Domain\Exceptions\Constraint\BusDeletionException;
use App\Domain\Exceptions\Constraint\BusReassignException;
use App\Domain\Exceptions\Constraint\CompanyDeletionException;
use App\Domain\Exceptions\Constraint\CompanyRouteExistsException;
use App\Domain\Exceptions\Constraint\DriverCardExistsException;
use App\Domain\Exceptions\Constraint\DriverDeletionException;
use App\Domain\Exceptions\Constraint\DriverReassignException;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
use App\Domain\Exceptions\Integrity\NoCompanyForRouteException;
use App\Domain\Exceptions\Integrity\NoDriverForCardException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyCompanyRoutesException;
use App\Domain\Exceptions\Integrity\TooManyDriverCardsException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Domain\Exceptions\Integrity\UnexpectedCardForDriverException;
use App\Domain\Exceptions\Integrity\UnexpectedCompanyForRouteException;

return [
    'constraint' => [
        'type' => 'Попытка нарушения ограничений',
        BusDeletionException::class => 'Автобус с назначенными водителями и валидаторами не может быть удален',
        BusReassignException::class => 'Автобус не может быть переназначен на другую компанию. Создайте новый',
        CompanyDeletionException::class => 'Компания со связанными данными не может быть удалена. Проверьте список автобусов, маршрутов, водителей и пользователей',
        CompanyRouteExistsException::class => 'Этот маршрут уже назначен компании',
        DriverCardExistsException::class => 'Эта карта уже назначена водителю',
        DriverDeletionException::class => 'Водитель с назначенной картой не может быть удален',
        DriverReassignException::class => 'Водитель не может быть переназначен на другую компанию. Создайте нового',
        RouteDeletionException::class => 'Маршурт со связанными данными не может быть удален. Проверьте список автобусов',
        RouteReassignException::class => 'Маршурт с назначенными автобусами не может быть переназначен на другую компанию',
    ],
    'integrity' => [
        'type' => 'Обнаруженное нарушение целостности бизнес-логики в данных',
        NoCompanyForRouteException::class => 'Не найдена историческая запись о привязке маршрута к компании',
        NoDriverForCardException::class => 'Не найдена историческая запись о привязке карты к водителю',
        NoTariffPeriodForDateException::class => 'Не найдена историческая запись о тарифе на дату',
        TooManyCompanyRoutesException::class => 'Обнаружено несколько исторических записей привязки маршрута к компаниям',
        TooManyDriverCardsException::class => 'Обнаружено несколько исторических записей привязки карты к водителям',
        TooManyTariffPeriodsForDateException::class => 'Обнаружено несколько исторических записей периодов действия тарифов',
        UnexpectedCardForDriverException::class => 'Обнаружена историческая запись с неожиданным водителем для карты',
        UnexpectedCompanyForRouteException::class => 'Обнаружена историческая запись с неожиданной компанией для маршрута',
    ],
];

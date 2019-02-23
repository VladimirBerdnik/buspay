<?php

use App\Domain\Exceptions\Constraint\BusDeletionException;
use App\Domain\Exceptions\Constraint\BusReassignException;
use App\Domain\Exceptions\Constraint\CardAuthorization\CardWithoutDriverAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnassignedValidatorCardAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnexpectedCardAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\WrongBusDriverAuthorizationException;
use App\Domain\Exceptions\Constraint\CompanyDeletionException;
use App\Domain\Exceptions\Constraint\CompanyRouteExistsException;
use App\Domain\Exceptions\Constraint\DriverCardExistsException;
use App\Domain\Exceptions\Constraint\DriverDeletionException;
use App\Domain\Exceptions\Constraint\DriverReassignException;
use App\Domain\Exceptions\Constraint\Payment\InvalidPaymentAmountException;
use App\Domain\Exceptions\Constraint\Payment\MissedPaymentException;
use App\Domain\Exceptions\Constraint\Payment\UnneededPaymentException;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
use App\Domain\Exceptions\Constraint\RouteSheetDeletionException;
use App\Domain\Exceptions\Integrity\InconsistentRouteSheetStateException;
use App\Domain\Exceptions\Integrity\NoCompanyForRouteException;
use App\Domain\Exceptions\Integrity\NoDriverForCardException;
use App\Domain\Exceptions\Integrity\NoTariffFareForDateException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyCardDriversException;
use App\Domain\Exceptions\Integrity\TooManyCompanyRoutesException;
use App\Domain\Exceptions\Integrity\TooManyDriverCardsException;
use App\Domain\Exceptions\Integrity\TooManyTariffFaresForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Domain\Exceptions\Integrity\UnexpectedCardForDriverException;
use App\Domain\Exceptions\Integrity\UnexpectedCompanyForRouteException;
use Dingo\Api\Exception\RateLimitExceededException;

return [
    'constraint' => [
        'type' => 'Попытка нарушения ограничений',
        BusDeletionException::class => 'Автобус с назначенными водителями, валидаторами или маршрутными листами не может быть удален',
        BusReassignException::class => 'Автобус не может быть переназначен на другую компанию. Создайте новый',
        CompanyDeletionException::class => 'Компания со связанными данными не может быть удалена. Проверьте список автобусов, маршрутов, водителей, пользователей и маршрутных листов',
        CompanyRouteExistsException::class => 'Этот маршрут уже назначен компании',
        DriverCardExistsException::class => 'Эта карта уже назначена водителю',
        DriverDeletionException::class => 'Водитель с назначенной картой не может быть удален',
        DriverReassignException::class => 'Водитель не может быть переназначен на другую компанию. Создайте нового',
        RouteDeletionException::class => 'Маршурт со связанными данными не может быть удален. Проверьте список автобусов и маршрутных листов',
        RouteSheetDeletionException::class => 'Маршуртный лист с транзакциями не может быть удален',
        RouteReassignException::class => 'Маршурт с назначенными автобусами не может быть переназначен на другую компанию',
        CardWithoutDriverAuthorizationException::class => 'Авторизация водительской картой без водителя',
        UnassignedValidatorCardAuthorizationException::class => 'Авторизация на валидаторе, не зарегистрированном на автобусе',
        UnexpectedCardAuthorizationException::class => 'Авторизация на валидаторе с неподдерживаемым типом карты',
        WrongBusDriverAuthorizationException::class => 'Авторизация водителя в автобусе другой транспортной компании',
        InvalidPaymentAmountException::class => 'Указанная сумма платежа по тарифу на указанную дату для авторизованного типа карты не совпадает с тарифом',
        MissedPaymentException::class => 'Платеж за авторизацию не списан',
        UnneededPaymentException::class => 'Списан платеж с карты, для которой это не предусмотрено',
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
        InconsistentRouteSheetStateException::class => 'Обнаружено неожиданное состояние маршрутных листов. Подробнее в логах',
        NoTariffFareForDateException::class => 'Не указана сумма платежа для тарифа и типа карты на дату',
        TooManyCardDriversException::class => 'Обнаружено несколько исторических записей назначения карты водителю',
        TooManyTariffFaresForDateException::class => 'Обнаружено несколько записей с указанием суммы проезда для тарифа на дату',
    ],
    'general' => [
        RateLimitExceededException::class => 'Вы превысили допустимое число запросов. Пожалуйста, повторите попытку позднее',
    ],
];

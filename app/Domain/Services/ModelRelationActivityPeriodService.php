<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\Constraint\ActivityPeriodExistsException;
use App\Domain\Exceptions\Integrity\TooManyActivityPeriodsException;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodMaster;
use App\Extensions\ActivityPeriod\IActivityPeriodRelated;
use App\Extensions\EntityService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Dto;
use Saritasa\Laravel\Validation\DateRuleSet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Service that can deal with parent to related records assignments activity periods.
 */
abstract class ModelRelationActivityPeriodService extends EntityService
{
    public const ACTIVITY_PERIOD_FROM_ATTRIBUTE = 'active_from';
    public const ACTIVITY_PERIOD_TO_ATTRIBUTE = 'active_to';

    /**
     * Returns model class name that is handled by this activity period service.
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return $this->getRepository()->getModelClass();
    }

    /**
     * Returns activity period model instance.
     *
     * @return IActivityPeriod
     */
    protected function getActivityPeriodModelInstance(): IActivityPeriod
    {
        $modelClass = $this->getModelClass();

        return new $modelClass();
    }

    /**
     * Returns validation rules of activity period values.
     *
     * @param IActivityPeriod $model Model to retrieve activity periods values to build validation rules
     * @param IActivityPeriodMaster $masterRecord Master record of activity period to build validation rules
     * @param IActivityPeriodRelated $relatedRecord Related record of activity period to build validation rules
     *
     * @return GenericRuleSet[]
     */
    protected function getActivityPeriodValidationRules(
        IActivityPeriod $model,
        IActivityPeriodMaster $masterRecord,
        IActivityPeriodRelated $relatedRecord
    ): array {
        return [
            static::ACTIVITY_PERIOD_FROM_ATTRIBUTE => Rule::required()->date()
                ->when($model->getActiveTo(), function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->before(static::ACTIVITY_PERIOD_TO_ATTRIBUTE);
                }),
            static::ACTIVITY_PERIOD_TO_ATTRIBUTE => Rule::nullable()->date()
                ->when($model->getActiveTo(), function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->after(static::ACTIVITY_PERIOD_FROM_ATTRIBUTE);
                }),
            $model->masterModelRelationAttribute() => Rule::required()->modelExists(get_class($masterRecord)),
            $model->relatedModelRelationAttribute() => Rule::required()->modelExists(get_class($relatedRecord)),
        ];
    }

    /**
     * Opens new master to related record assignment period.
     *
     * @param IActivityPeriodMaster $master Master activity period record to open period for
     * @param IActivityPeriodRelated $related Related activity period record to open period with
     * @param Carbon|null $activeFrom Start date of activity period
     *
     * @return IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws ActivityPeriodExistsException
     * @throws TooManyActivityPeriodsException
     */
    protected function openPeriod(
        IActivityPeriodMaster $master,
        IActivityPeriodRelated $related,
        ?Carbon $activeFrom = null
    ): IActivityPeriod {
        $modelClass = $this->getModelClass();

        Log::debug("Assignment {$modelClass} from [{$master->getKey()}] to [{$related->getKey()}] assign attempt");

        $activeFrom = $activeFrom ?? Carbon::now();

        /**
         * Activity period details.
         *
         * @var Dto $activityPeriodData
         */
        $activityPeriodData = new $modelClass([
            static::ACTIVITY_PERIOD_FROM_ATTRIBUTE => $activeFrom,
            $this->getActivityPeriodModelInstance()->relatedModelRelationAttribute() => $related->getKey(),
            $this->getActivityPeriodModelInstance()->masterModelRelationAttribute() => $master->getKey(),
        ]);

        /**
         * New activity period record.
         *
         * @var IActivityPeriod|Model $activityPeriod
         */
        $activityPeriod = new $modelClass($activityPeriodData->toArray());

        Validator::validate(
            $activityPeriodData->toArray(),
            $this->getActivityPeriodValidationRules($activityPeriod, $master, $related)
        );

        /**
         * Existing activity period record for same model.
         *
         * @var IActivityPeriod $existingActivityPeriod
         */
        $existingActivityPeriod = $this->getPeriodFor($master, $activeFrom);

        if ($existingActivityPeriod) {
            throw new ActivityPeriodExistsException($existingActivityPeriod);
        }

        $this->getRepository()->create($activityPeriod);

        Log::debug("Assignment {$modelClass} period [{$activityPeriod->getKey()}] created");

        return $activityPeriod;
    }

    /**
     * Closes historical record assignment period.
     *
     * @param IActivityPeriod $activityPeriod Activity period to close
     * @param Carbon|null $activeTo Date at which period should be closed
     *
     * @return IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    protected function closePeriod(IActivityPeriod $activityPeriod, ?Carbon $activeTo = null): IActivityPeriod
    {
        Log::debug("Close assignment {$this->getModelClass()} period [{$activityPeriod->getKey()}] attempt");

        /**
         * Activity period to close.
         *
         * @var Model|IActivityPeriod $activityPeriod
         */
        $activityPeriod->{static::ACTIVITY_PERIOD_TO_ATTRIBUTE} = $activeTo ?? Carbon::now();

        Validator::validate(
            $activityPeriod->toArray(),
            $this->getActivityPeriodValidationRules(
                $activityPeriod,
                $activityPeriod->getMasterRecord(),
                $activityPeriod->getRelatedRecord()
            )
        );

        $this->getRepository()->save($activityPeriod);

        Log::debug("Assignment {$this->getModelClass()} period [{$activityPeriod->getKey()}] closed");

        return $activityPeriod;
    }

    /**
     * Returns master to related record assignment that was active at passed date.
     *
     * @param IActivityPeriodMaster $model Model to retrieve activity period for
     * @param Carbon|null $date Date to find activity period record
     *
     * @return IActivityPeriod|null
     *
     * @throws TooManyActivityPeriodsException
     */
    protected function getPeriodFor(IActivityPeriodMaster $model, ?Carbon $date = null): ?IActivityPeriod
    {
        $date = $date ?? Carbon::now();

        $periods = $this->getRepository()->getWith(
            [],
            [],
            [
                [$this->getActivityPeriodModelInstance()->masterModelRelationAttribute(), $model->getKey()],
                [static::ACTIVITY_PERIOD_FROM_ATTRIBUTE, '<=', $date],
                [
                    [
                        [static::ACTIVITY_PERIOD_TO_ATTRIBUTE, '=', null, 'or'],
                        [static::ACTIVITY_PERIOD_TO_ATTRIBUTE, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($periods->count() > 1) {
            throw new TooManyActivityPeriodsException($date, $periods);
        }

        if ($periods->count() === 1) {
            return $periods->first();
        }

        return null;
    }
}

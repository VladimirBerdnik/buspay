<?php

namespace App\Domain\Reports;

use DB;
use Saritasa\LaravelRepositories\DTO\Criterion;
use Saritasa\LaravelRepositories\DTO\SortOptions;

class SqlGeneralReportService
{
    /**
     * Mapping of visible to user fields. These fields user can select to display.
     *
     * @var string[]
     */
    protected $selectableFieldsMapping = [
        GeneralReportFields::COMPANY => 'companies.name',
        GeneralReportFields::ROUTE => 'routes.name',
        GeneralReportFields::TARIFF => 'tariffs.name',
        GeneralReportFields::BUS => 'buses.state_number',
        GeneralReportFields::DRIVER => 'drivers.full_name',
        GeneralReportFields::VALIDATOR => 'validators.serial_number',
        GeneralReportFields::CARD_TYPE => 'card_types.slug',
        GeneralReportFields::DATE => 'date(transactions.authorized_at)',
    ];

    /**
     * Mapping of report filterable fields. User can perform filtering by these fields.
     *
     * @var string[]
     */
    protected $filterableFieldsMapping = [
        GeneralReportFields::COMPANY => 'companies.id',
        GeneralReportFields::ROUTE => 'routes.id',
        GeneralReportFields::TARIFF => 'tariffs.id',
        GeneralReportFields::BUS => 'buses.id',
        GeneralReportFields::DRIVER => 'drivers.id',
        GeneralReportFields::VALIDATOR => 'validators.id',
        GeneralReportFields::CARD_TYPE => 'card_types.id',
        GeneralReportFields::DATE => 'transactions.authorized_at',
    ];

    /**
     * Returns data of general report in requested format.
     *
     * @param string[] $requestedFields Requested report fields
     * @param Criterion[] $criteria Criteria that exported report items should match
     * @param SortOptions $sortOptions Required sort options of report items
     *
     * @return mixed[]
     */
    public function getData(array $requestedFields, array $criteria, SortOptions $sortOptions): array
    {
        $selectedColumns = [];
        foreach ($requestedFields as $field) {
            $selectedColumns[] = "{$this->selectableFieldsMapping[$field]} as {$field}";
        }
        $selectedColumns[] = 'count(transactions.id) as transactionsCount';
        $selectedColumns[] = 'sum(transactions.amount) as transactionsSum';

        $groupableFields = array_only($this->selectableFieldsMapping, $requestedFields);

        $sql = <<<sql
from companies
       inner join route_sheets on companies.id = route_sheets.company_id
       inner join buses on route_sheets.bus_id = buses.id
       inner join drivers on route_sheets.driver_id = drivers.id
       inner join routes on route_sheets.route_id = routes.id
       inner join transactions on route_sheets.id = transactions.route_sheet_id
       inner join cards on transactions.card_id = cards.id
       inner join card_types on cards.card_type_id = card_types.id
       inner join tariffs on transactions.tariff_id = tariffs.id
       inner join validators on transactions.validator_id = validators.id
sql;

        $query = DB::query()
            ->select($selectedColumns)
            ->fromRaw($sql)
            ->groupBy($groupableFields)
            ->orderBy($this->selectableFieldsMapping[$sortOptions->orderBy], $sortOptions->sortOrder);

        foreach ($criteria as $criterion) {
            $field = $this->filterableFieldsMapping[$criterion->attribute];
            if ($criterion->operator === 'in') {
                $query->whereIn($field, $criterion->value, $criterion->boolean);
            } else {
                $query->where($field, $criterion->operator, $criterion->value, $criterion->boolean);
            }
        }

        return $query->get();
    }
}

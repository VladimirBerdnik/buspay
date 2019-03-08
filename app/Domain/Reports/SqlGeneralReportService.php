<?php

namespace App\Domain\Reports;

use App\Domain\Dto\Filters\GeneralReportFilterData;
use App\Domain\Dto\Reports\GeneralReportRow;
use App\Domain\Enums\CardTypesIdentifiers;
use DB;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;

/**
 * Service that allows to retrieve general report data.
 */
class SqlGeneralReportService
{
    /**
     * Mapping of visible to user fields. These fields user can select to display.
     *
     * @var string[]
     */
    protected $selectableFieldsMapping = [];

    /**
     * Service that allows to retrieve general report data.
     */
    public function __construct()
    {
        $this->selectableFieldsMapping = [
            GeneralReportFields::COMPANY => 'companies.name',
            GeneralReportFields::ROUTE => 'routes.name',
            GeneralReportFields::TARIFF => 'tariffs.name',
            GeneralReportFields::BUS => 'buses.state_number',
            GeneralReportFields::DRIVER => 'drivers.full_name',
            GeneralReportFields::VALIDATOR => 'validators.serial_number',
            GeneralReportFields::CARD_TYPE => 'card_types.slug',
            GeneralReportFields::DATE => DB::raw('DATE(transactions.authorized_at)'),
            GeneralReportFields::HOUR => DB::raw('EXTRACT(HOUR from transactions.authorized_at)'),
        ];
    }

    /**
     * Mapping of report filterable fields. User can perform filtering by these fields.
     *
     * @var string[]
     */
    protected $filterableFieldsMapping = [
        GeneralReportFilterData::COMPANY_ID => 'companies.id',
        GeneralReportFilterData::ROUTE_ID => 'routes.id',
        GeneralReportFilterData::TARIFF_ID => 'tariffs.id',
        GeneralReportFilterData::BUS_ID => 'buses.id',
        GeneralReportFilterData::DRIVER_ID => 'drivers.id',
        GeneralReportFilterData::VALIDATOR_ID => 'validators.id',
        GeneralReportFilterData::CARD_TYPE_ID => 'card_types.id',
        GeneralReportFilterData::AUTHORIZED_FROM => 'transactions.authorized_at',
        GeneralReportFilterData::AUTHORIZED_TO => 'transactions.authorized_at',
    ];

    /**
     * Returns data of general report in requested format.
     *
     * @param string[] $requestedFields Requested report fields
     * @param GeneralReportFilterData $reportFilterData Filters to retrieve general report data
     *
     * @return Collection|mixed[]
     */
    public function getData(
        array $requestedFields,
        GeneralReportFilterData $reportFilterData
    ): Collection {
        $selectedColumns = [];
        foreach ($requestedFields as $field) {
            $mappedField = $this->selectableFieldsMapping[$field];
            $selectedColumnWithAlias = "{$mappedField} as {$field}";
            $selectedColumns[] = $mappedField instanceof Expression
                ? DB::raw($selectedColumnWithAlias)
                : $selectedColumnWithAlias;
        }
        $selectedColumns[] = DB::raw('count(transactions.id) as transactionsCount');
        $selectedColumns[] = DB::raw('sum(transactions.amount) as transactionsSum');

        $groupableFields = array_only($this->selectableFieldsMapping, $requestedFields);

        $sql = <<<sql
companies
    inner join route_sheets on companies.id = route_sheets.company_id
    inner join buses on route_sheets.bus_id = buses.id
    left join drivers on route_sheets.driver_id = drivers.id
    left join routes on route_sheets.route_id = routes.id
    inner join transactions on route_sheets.id = transactions.route_sheet_id
    inner join cards on transactions.card_id = cards.id
    inner join card_types on cards.card_type_id = card_types.id
    left join tariffs on transactions.tariff_id = tariffs.id
    inner join validators on transactions.validator_id = validators.id
sql;

        $query = DB::query()
            ->select($selectedColumns)
            ->fromRaw($sql);

        if ($groupableFields) {
            $query->groupBy($groupableFields);
        }

        $reportFilters = array_filter($reportFilterData->toArray());
        foreach ($reportFilters as $attribute => $value) {
            $field = $this->filterableFieldsMapping[$attribute];
            if (!$field) {
                continue;
            }

            switch ($attribute) {
                case GeneralReportFilterData::AUTHORIZED_FROM:
                    $operator = '>=';
                    break;
                case GeneralReportFilterData::AUTHORIZED_TO:
                    $operator = '<=';
                    break;
                default:
                    $operator = '=';
            }

            $query->where($field, $operator, $value);
        }

        $query->where('card_types.id', '!=', CardTypesIdentifiers::DRIVER);
        $query->where('card_types.id', '!=', CardTypesIdentifiers::SERVICE);

        $reportData = $query->get();

        return $reportData->map(function ($reportRow) use ($requestedFields) {
            $rowData = (array)$reportRow;

            if (in_array(GeneralReportRow::CARD_TYPE, $requestedFields)) {
                $rowData[GeneralReportRow::CARD_TYPE] = trans("cardTypes.{$rowData[GeneralReportFields::CARD_TYPE]}");
            }

            return new GeneralReportRow($rowData);
        });
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Dto\Filters\GeneralReportFilterData;
use App\Domain\Export\GeneralReportExporter;
use App\Domain\Reports\SqlGeneralReportService;
use App\Http\Requests\Api\FilteredListRequest;
use App\Http\Requests\Api\GeneralReportDataRequest;
use Dingo\Api\Http\Response;
use Saritasa\Transformers\IDataTransformer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * General report API controller. Allows to retrieve report data in requested format.
 */
class GeneralReportApiController extends BaseApiController
{
    /**
     * Service that allows to retrieve general report data.
     *
     * @var SqlGeneralReportService
     */
    private $generalReportService;

    /**
     * Service that allows to export CSV report.
     *
     * @var GeneralReportExporter
     */
    private $generalReportExporter;

    /**
     * Roles requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param SqlGeneralReportService $generalReportService Service that allows to retrieve general report data
     * @param GeneralReportExporter $generalReportExporter Service that allows to export CSV report
     */
    public function __construct(
        IDataTransformer $transformer,
        SqlGeneralReportService $generalReportService,
        GeneralReportExporter $generalReportExporter
    ) {
        parent::__construct($transformer);
        $this->generalReportService = $generalReportService;
        $this->generalReportExporter = $generalReportExporter;
    }

    /**
     * Returns general report data.
     *
     * @param GeneralReportDataRequest $request Request with parameters to retrieve sorted filtered data of general
     *     report
     *
     * @return Response
     */
    public function index(GeneralReportDataRequest $request): Response
    {
        $reportData = $this->generalReportService->getData(
            $request->fields ?? [],
            $this->getReportFilterData($request)
        );

        return $this->response->collection(
            $reportData,
            $this->transformer
        );
    }

    /**
     * Exports general report.
     *
     * @param GeneralReportDataRequest $request Request with parameters to retrieve sorted filtered data of general
     *     report
     *
     * @return BinaryFileResponse
     */
    public function export(GeneralReportDataRequest $request): BinaryFileResponse
    {
        $exportedFileName = $this->generalReportExporter->export(
            $request->fields ?? [],
            $this->getReportFilterData($request)
        );

        return new BinaryFileResponse($exportedFileName);
    }

    /**
     * Returns general report filter data that should be applied to retrieve data.
     *
     * @param FilteredListRequest $request General report request with filtering details
     *
     * @return GeneralReportFilterData
     */
    private function getReportFilterData(FilteredListRequest $request): GeneralReportFilterData
    {
        $filters = $request->getFilters();
        $companyId = $this->singleCompanyUser()
            ? $this->user->company_id
            : $filters[GeneralReportFilterData::COMPANY_ID] ?? null;

        $reportFilterData = new GeneralReportFilterData([
            GeneralReportFilterData::AUTHORIZED_FROM => $request->activeFrom(),
            GeneralReportFilterData::AUTHORIZED_TO => $request->activeTo(),
            GeneralReportFilterData::COMPANY_ID => $companyId,
            GeneralReportFilterData::CARD_TYPE_ID => $filters[GeneralReportFilterData::CARD_TYPE_ID] ?? null,
            GeneralReportFilterData::TARIFF_ID => $filters[GeneralReportFilterData::TARIFF_ID] ?? null,
            GeneralReportFilterData::VALIDATOR_ID => $filters[GeneralReportFilterData::VALIDATOR_ID] ?? null,
            GeneralReportFilterData::ROUTE_ID => $filters[GeneralReportFilterData::ROUTE_ID] ?? null,
            GeneralReportFilterData::BUS_ID => $filters[GeneralReportFilterData::BUS_ID] ?? null,
            GeneralReportFilterData::DRIVER_ID => $filters[GeneralReportFilterData::DRIVER_ID] ?? null,
        ]);

        return $reportFilterData;
    }
}

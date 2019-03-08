<?php

namespace App\Domain\Export;

use App\Domain\Dto\Filters\GeneralReportFilterData;
use App\Domain\Reports\SqlGeneralReportService;
use Log;

/**
 * General report data exporter.
 */
class GeneralReportExporter extends CsvFileExporter
{
    /**
     * Service that allows to retrieve general report data.
     *
     * @var SqlGeneralReportService
     */
    private $generalReportService;

    /**
     * General report data exporter.
     *
     * @param SqlGeneralReportService $generalReportService Service that allows to retrieve general report data
     */
    public function __construct(SqlGeneralReportService $generalReportService)
    {
        $this->generalReportService = $generalReportService;
    }

    /**
     * Exports route sheets records into file with respect to requested filter and sorting details.
     *
     * @param string[] $fields Requested for report fields
     * @param GeneralReportFilterData $reportFilterData Report filter details
     *
     * @return string
     */
    public function export(array $fields, GeneralReportFilterData $reportFilterData): string
    {
        Log::debug(
            'General report export process started',
            ['fields' => $fields, 'filter' => $reportFilterData->toArray()]
        );

        $filename = $this->getTempFileName();
        $file = $this->openFile($filename);

        $headers = [];
        foreach ($fields as $field) {
            $headers[] = trans("generalReportExport.{$field}");
        }
        $headers[] = trans("generalReportExport.count");
        $headers[] = trans("generalReportExport.sum");
        $this->putRow($file, $headers);

        $reportData = $this->generalReportService->getData($fields, $reportFilterData);

        foreach ($reportData as $reportRow) {
            $rowData = [];
            foreach ($fields as $field) {
                $rowData[] = $reportRow->{$field};
            }
            $rowData[] = $reportRow->count;
            $rowData[] = $reportRow->sum;
            $this->putRow($file, $rowData);
        }

        $this->closeFile($file);

        Log::debug("General report export process to file {$filename} finished");

        return $filename;
    }
}

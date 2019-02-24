<?php

namespace App\Domain\Export;

/**
 * Csv file exporter.
 */
abstract class CsvFileExporter
{
    /**
     * Default size of retrieved chunk to export.
     *
     * @var integer
     */
    protected $exportChunkSize = 100;

    /**
     * Generates random name of the file in temporary storage.
     *
     * @return string
     */
    protected function getTempFileName(): string
    {
        return tempnam(sys_get_temp_dir(), 'csvExport');
    }

    /**
     * Opens file for writing.
     *
     * @param string $filename File to open
     *
     * @return boolean|resource
     */
    protected function openFile(string $filename)
    {
        return fopen($filename, 'w+');
    }

    /**
     * Inserts row into CSV file.
     *
     * @param resource $file File to put row into
     * @param string[] $row Row to put
     */
    protected function putRow($file, array $row): void
    {
        fputcsv($file, $row);
    }

    /**
     * Closes finished file.
     *
     * @param resource $file File to close
     */
    protected function closeFile($file): void
    {
        fclose($file);
    }
}

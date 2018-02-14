<?php

namespace Dpiquet\SpreadsheetMapping;

use Dpiquet\Mapping\Exception\MappingIncompleteException;
use Dpiquet\Mapping\Mapping;
use Dpiquet\SpreadsheetMapping\MappedRow\MappedRow;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Map spreadsheet from columns names
 *
 */
class SpreadsheetMapping implements \Iterator {

    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @var array $map
     */
    private $map;

    /**
     * @var Worksheet
     */
    private $sheet;

    /**
     * @var RowIterator
     */
    private $rowIterator;

    /**
     * SpreadsheetMapping constructor.
     * @param Worksheet $sheet
     * @param Mapping $mapping
     * @throws MappingIncompleteException
     */
    public function __construct(Worksheet $sheet, Mapping $mapping) {
        $this->sheet = $sheet;
        $this->mapping = $mapping;

        // Initialize mapping
        $columns = [];
        $this->rowIterator = $this->sheet->getRowIterator();
        foreach ($this->rowIterator->current()->getCellIterator() as $cell) {
            $columns[] = $cell->getValue();
        }

        $this->map = $this->mapping->map($columns);
    }

    public function current()
    {
        return new MappedRow($this->map, $this->rowIterator->current());
    }

    public function next()
    {
        $this->rowIterator->next();
    }

    public function key()
    {
        return $this->rowIterator->key();
    }

    public function valid()
    {
        return $this->rowIterator->valid(); // TODO: check values exists in this row
    }

    public function rewind()
    {
        try {
            return $this->rowIterator->seek(2);
        } catch (Exception $e) {
            // TODO: what should we do if sheet is less than 2 lines ?
        }
    }
}
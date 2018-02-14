<?php

namespace Dpiquet\SpreadsheetMapping\MappedRow;


use Dpiquet\Mapping\Mapping;
use Dpiquet\SpreadsheetMapping\MappedRow\Exception\InvalidKeyException;
use Dpiquet\SpreadsheetMapping\MappedRow\Exception\MissingValueException;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class MappedRow
{
    /**
     * @var array
     */
    private $map;

    /**
     * @var Row
     */
    private $row;

    /**
     * @var array
     */
    private $values = [];

    /**
     * MappedRow constructor.
     * @param array $map
     * @param Row $row
     */
    public function __construct(array $map, Row $row)
    {
        $this->map = $map;
        $this->row = $row;

        foreach ($this->row->getCellIterator() as $cell) {
            $this->values[] = $cell->getValue();
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidKeyException
     * @throws MissingValueException
     */
    public function get($key) {
        if (!in_array($key, $this->map)) {
            throw new InvalidKeyException();
        }

        if ($this->map[$key] > count($this->values)) {
            throw new MissingValueException();
        }

        return $this->values[$this->map[$key]];
    }
}
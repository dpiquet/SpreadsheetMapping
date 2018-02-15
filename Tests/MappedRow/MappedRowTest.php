<?php

namespace Dpiquet\Spreadsheetmapping\MappedRow\Tests;

use Dpiquet\SpreadsheetMapping\MappedRow\Exception\InvalidKeyException;
use Dpiquet\SpreadsheetMapping\MappedRow\MappedRow;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PHPUnit\Framework\TestCase;

class MappedRowTest extends TestCase
{

    /**
     * @dataProvider invalidKeyDataProvider
     * @expectedException \Dpiquet\SpreadsheetMapping\MappedRow\Exception\InvalidKeyException
     */
    public function testinvalidKey($key, $cols, $row)
    {
        $mappedRow = new MappedRow($cols, $row);
        $mappedRow->get($key);
    }

    public function invalidKeyDataProvider()
    {
        $cols = [
            'a' => 0,
            'c' => 1,
        ];
        $mockRow = $this->createMock(Row::class);
        $mockRow
            ->method('getCellIterator')
            ->willReturn(new \ArrayIterator([$this->generateMockCell('a'), $this->generateMockCell('b')]))
        ;
        yield ['invalidCellKey', $cols, $mockRow];

        $numericCols = [
            455 => 1,
            377 => 0,
        ];
        $numericMockRow = $this->createMock(Row::class);
        $numericMockRow
            ->method('getCellIterator')
            ->willReturn(new \ArrayIterator([$this->generateMockCell(4), $this->generateMockCell(5)]))
        ;
        yield ['invalidCellKey', $numericCols, $numericMockRow];
    }

    /**
     * Generate a mock Cell
     *
     * @param mixed $val
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function generateMockCell($val)
    {
        $mockCell = $this->createMock(Cell::class);
        $mockCell
            ->method('getValue')
            ->willReturn($val)
        ;

        return $mockCell;
    }

}
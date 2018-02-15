<?php

namespace Dpiquet\SpreadsheetMapping\Tests;

use Dpiquet\Mapping\Mapping;
use Dpiquet\SpreadsheetMapping\SpreadsheetMapping;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPUnit\Framework\TestCase;

class SpreadSheetMappingTest extends TestCase
{

    /**
     * @param string $filename
     * @dataProvider basicIntegrationFilenameProvider
     */
    public function testBasicIntegration($filename)
    {
        $workbook = IOFactory::load($filename);
        $worksheet = $workbook->getSheet(0);

        $mapping = new Mapping();
        $mapping
            ->addMapping('col1', ['test1'])
            ->addMapping('col2', ['test2'])
            ->addMapping('col3', ['test3'])
        ;

        $mappedSheet = new SpreadsheetMapping($worksheet, $mapping); // Throw exception if incorrect mapping
        foreach($mappedSheet as $mappedRowValues) {
            $this->assertStringEndsWith('1', (string) $mappedRowValues->get('col1'));
            $this->assertStringEndsWith('2', (string) $mappedRowValues->get('col2'));
            $this->assertStringEndsWith('3', (string) $mappedRowValues->get('col3')); // Throws Exception if not mapped
        }
    }

    /**
     * @return \Generator|string[]
     */
    public function basicIntegrationFilenameProvider()
    {
        yield ['Tests/testData/simpleok.xlsx'];
        yield ['Tests/testData/gaps.xlsx'];
    }

    public function testOnlyColumns()
    {
        $workbook = IOFactory::load('Tests/testData/simpleok.xlsx');
        $worksheet = $workbook->getSheet(0);
        $mapping = new Mapping();
        $mapping
            ->addMapping('col1', ['test1'])
            ->addMapping('col2', ['test2'])
            ->addMapping('col3', ['test3'])
        ;

        $mappedSheet = new SpreadsheetMapping($worksheet, $mapping);

        $lines = 0;
        foreach ($mappedSheet as $line) {
            $line++;
        }

        $this->assertEquals(0, $lines);
    }

    public function testMappedColumns()
    {
        $workbook = IOFactory::load('Tests/testData/simpleok.xlsx');
        $worksheet = $workbook->getSheet(0);
        $mapping = new Mapping();
        $mapping
            ->addMapping('col1', ['test1'])
            ->addMapping('col2', ['test2'])
            ->addMapping('col3', ['test3'])
        ;

        $mappedSheet = new SpreadsheetMapping($worksheet, $mapping);
        $columnKeys = $mappedSheet->getMappedColumns();

        $this->assertContains('col1', $columnKeys);
        $this->assertContains('col2', $columnKeys);
        $this->assertContains('col3', $columnKeys);
    }

}
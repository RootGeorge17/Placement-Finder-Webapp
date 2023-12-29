<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/IndustriesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class IndustriesDataSetTest extends TestCase
{
    protected $industriesDataSet;
    protected function setUp(): void
    {
        $this->industriesDataSet = new IndustriesDataSet();
    }

    protected function tearDown(): void
    {
        $this->industriesDataSet = null;
    }

    public function testFetchAllIndustries()
    {
        $industries = $this->industriesDataSet->fetchAllIndustries();
        $this->assertIsArray($industries, 'IndustriesDataSet::fetchAllIndustries() should return an array');
        $this->assertNotEmpty($industries, 'IndustriesDataSet::fetchAllIndustries() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(Industry::class, $industries, 'IndustriesDataSet::fetchAllIndustries() should return an array of Industry objects');
    }

    public function testFetchIndustryNameById()
    {
        $industryName = $this->industriesDataSet->fetchIndustryNameById(1);
        $this->assertIsString($industryName, 'IndustriesDataSet::fetchIndustryNameById() should return a string');
        $this->assertNotEmpty($industryName, 'IndustriesDataSet::fetchIndustryNameById() should return a non-empty string');
        $this->assertEquals('Data Science', $industryName, 'IndustriesDataSet::fetchIndustryNameById() should return the correct industry name');
    }

    public function testFetchIndustryById()
    {
        $industry = $this->industriesDataSet->fetchIndustryById(1);
        $this->assertInstanceOf(Industry::class, $industry, 'IndustriesDataSet::fetchIndustryById() should return an Industry object');
        $this->assertEquals(1, $industry->getId(), 'IndustriesDataSet::fetchIndustryById() should return an Industry object with the correct id');
        $this->assertEquals('Data Science', $industry->getIndustry(), 'IndustriesDataSet::fetchIndustryById() should return an Industry object with the correct industry name');
    }
}
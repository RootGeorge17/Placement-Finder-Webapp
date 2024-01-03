<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/PlacementsDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class PlacementsDataSetTest extends TestCase
{
    protected $placementsDataSet;

    protected function setUp(): void
    {
        $this->placementsDataSet = new PlacementsDataSet();
    }

    protected function tearDown(): void
    {
        $this->placementsDataSet = null;
    }

    public function testFetchAllPlacements()
    {
        $placements = $this->placementsDataSet->fetchAllPlacements();
        $this->assertIsArray($placements, 'PlacementsDataSet::fetchAllPlacements() should return an array');
        $this->assertNotEmpty($placements, 'PlacementsDataSet::fetchAllPlacements() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(PlacementData::class, $placements, 'PlacementsDataSet::fetchAllPlacements() should return an array of PlacementData objects');
    }

    public function testFetchPlacementsByCompanyId()
    {
        $placements = $this->placementsDataSet->fetchPlacementsByCompanyId(1);
        $this->assertIsArray($placements, 'PlacementsDataSet::fetchPlacementsByCompanyId() should return an array');
        $this->assertNotEmpty($placements, 'PlacementsDataSet::fetchPlacementsByCompanyId() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(PlacementData::class, $placements, 'PlacementsDataSet::fetchPlacementsByCompanyId() should return an array of PlacementData objects');
    }

    public function testFetchPlacementById()
    {
        $placement = $this->placementsDataSet->fetchPlacementById(1);
        $this->assertInstanceOf(PlacementData::class, $placement, 'PlacementsDataSet::fetchPlacementById() should return a PlacementData object');
        $this->assertNotEmpty($placement, 'PlacementsDataSet::fetchPlacementById() should return a non-empty PlacementData object');
        $this->assertEquals(1, $placement->getId(), 'PlacementsDataSet::fetchPlacementById() should return a PlacementData object with the correct id');
        $this->assertEquals(2, $placement->getCompanyId(), 'PlacementsDataSet::fetchPlacementById() should return a PlacementData object with the correct company id');
        $this->assertEquals(1, $placement->getIndustry(), 'PlacementsDataSet::fetchPlacementById() should return a PlacementData object with the correct industry');
    }


}
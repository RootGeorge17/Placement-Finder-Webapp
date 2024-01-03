<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/ProficienciesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class ProficienciesDataSetTest extends TestCase
{
    protected $proficienciesDataSet;

    protected function setUp(): void
    {
        $this->proficienciesDataSet = new ProficienciesDataSet();
    }

    protected function tearDown(): void
    {
        $this->proficienciesDataSet = null;
    }

    public function testFetchAllProficiencies()
    {
        $proficiencies = $this->proficienciesDataSet->fetchAllProficiencies();
        $this->assertIsArray($proficiencies, 'ProficienciesDataSet::fetchAllProficiencies() should return an array');
        $this->assertNotEmpty($proficiencies, 'ProficienciesDataSet::fetchAllProficiencies() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(Proficiency::class, $proficiencies, 'ProficienciesDataSet::fetchAllProficiencies() should return an array of Proficiency objects');
    }

    public function testFetchProficiencyById()
    {
        $proficiency = $this->proficienciesDataSet->fetchProficiencyById(1);
        $this->assertInstanceOf(Proficiency::class, $proficiency, 'ProficienciesDataSet::fetchProficiencyById() should return a Proficiency object');
        $this->assertEquals(1, $proficiency->getId(), 'ProficienciesDataSet::fetchProficiencyById() should return a Proficiency object with the correct id');
        $this->assertEquals('Beginner', $proficiency->getProficiency(), 'ProficienciesDataSet::fetchProficiencyById() should return a Proficiency object with the correct proficiency');
    }
}
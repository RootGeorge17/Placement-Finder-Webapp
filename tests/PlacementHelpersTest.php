<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/Extensions/PlacementHelpers.php'));
require_once (base_path('models/DataSets/SkillsDataSet.php'));
require_once (base_path('models/DataSets/ProficienciesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class PlacementHelpersTest extends TestCase
{
    protected $placementHelpers, $skillsDataSet, $proficienciesDataSet;

    protected function setUp(): void
    {
        $this->placementHelpers = new PlacementHelpers();
        $this->skillsDataSet = new SkillsDataSet();
        $this->proficienciesDataSet = new ProficienciesDataSet();
    }

    protected function tearDown(): void
    {
        $this->placementHelpers = null;
        $this->skillsDataSet = null;
        $this->proficienciesDataSet = null;
    }

    public function testGetProficiencyFromSkill()
    {
        $skill = $this->skillsDataSet->fetchSkillById(1);
        $allProficiencies = $this->proficienciesDataSet->fetchAllProficiencies();

        $proficiencyName = $this->placementHelpers->getProficiencyFromSkill($skill, $allProficiencies);
        $this->assertEquals('Expert', $proficiencyName, 'PlacementHelpers::getProficiencyFromSkill() should return the correct proficiency Name');
    }


}
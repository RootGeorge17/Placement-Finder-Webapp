<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/SkillsDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class SkillsDataSetTest extends TestCase
{
    protected $skillsDataSet;

    protected function setUp(): void
    {
        $this->skillsDataSet = new SkillsDataSet();
    }

    protected function tearDown(): void
    {
        $this->skillsDataSet = null;
    }

    public function testFetchAllSkills()
    {
        $skills = $this->skillsDataSet->fetchAllSkills();
        $this->assertIsArray($skills, 'SkillsDataSet::fetchAllSkills() should return an array');
        $this->assertNotEmpty($skills, 'SkillsDataSet::fetchAllSkills() should not return an empty array');
        $this->assertContainsOnlyInstancesOf(Skill::class, $skills, 'SkillsDataSet::fetchAllSkills() should return an array of Skill objects');
    }

    public function testFetchSkillById()
    {
        $skill = $this->skillsDataSet->fetchSkillById(1);
        $this->assertInstanceOf(Skill::class, $skill, 'SkillsDataSet::fetchSkillById() should return a Skill object');
        $this->assertEquals(1, $skill->getId(), 'SkillsDataSet::fetchSkillById() should return a Skill object with the correct id');
        $this->assertEquals('Programming', $skill->getSkillName(), 'SkillsDataSet::fetchSkillById() should return a Skill object with the correct skill name');
        $this->assertEquals(3, $skill->getProficiencyId(), 'SkillsDataSet::fetchSkillById() should return a Skill object with the correct proficiency');
    }

    public function testFetchSkillName()
    {
        $skills = $this->skillsDataSet->fetchSkillsByName('Programming');
        $this->assertIsArray($skills, 'SkillsDataSet::fetchSkillsByName() should return an array');
        $this->assertNotEmpty($skills, 'SkillsDataSet::fetchSkillsByName() should not return an empty array');
        $this->assertContainsOnlyInstancesOf(Skill::class, $skills, 'SkillsDataSet::fetchSkillsByName() should return an array of Skill objects');

        foreach ($skills as $skill) {
            $this->assertEquals('Programming', $skill->getSkillName(), 'SkillsDataSet::fetchSkillsByName() should return an array of Skill objects with the correct skill name');
        }

    }

}
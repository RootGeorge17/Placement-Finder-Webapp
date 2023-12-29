<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/StudentsDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class StudentsDataSetTest extends TestCase
{
    protected $studentsDataSet;

    protected function setUp(): void
    {
        $this->studentsDataSet = new StudentsDataSet();
    }

    protected function tearDown(): void
    {
        $this->studentsDataSet = null;
    }

    public function testFetchAllStudentData()
    {
        $students = $this->studentsDataSet->fetchAllStudentData();
        $this->assertIsArray($students, 'StudentsDataSet::fetchAllStudentData() should return an array');
        $this->assertNotEmpty($students, 'StudentsDataSet::fetchAllStudentData() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(StudentData::class, $students, 'StudentsDataSet::fetchAllStudentData() should return an array of StudentData objects');
    }

    public function testFetchStudentDataById()
    {
        $student = $this->studentsDataSet->fetchStudentDataById(1);
        $this->assertInstanceOf(StudentData::class, $student, 'StudentsDataSet::fetchStudentDataById() should return a StudentData object');
        $this->assertEquals(1, $student->getId(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct id');
        $this->assertEquals('London', $student->getLocation(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct location');
        $this->assertEquals(1, $student->getCourse(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct course');
        $this->assertEquals('University of Manchester', $student->getInstitution(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct institution');
        $this->assertEquals(1, $student->getPrefIndustry(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct preferred industry');
        $this->assertEquals(1, $student->getSkill1(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct skill 1');
        $this->assertEquals(4, $student->getSkill2(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct skill 2');
        $this->assertEquals(6, $student->getSkill3(), 'StudentsDataSet::fetchStudentDataById() should return a StudentData object with the correct skill 3');
    }
}
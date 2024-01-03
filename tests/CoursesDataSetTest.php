<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/CoursesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class CoursesDataSetTest extends TestCase
{
    protected $coursesDataSet;

    protected function setUp(): void
    {
        $this->coursesDataSet = new CoursesDataSet();
    }

    protected function tearDown(): void
    {
        $this->coursesDataSet = null;
    }

    public function testFetchAllCourses()
    {
        $courses = $this->coursesDataSet->fetchAllCourses();
        $this->assertIsArray($courses, 'CoursesDataSet::fetchAllCourses() should return an array');
        $this->assertNotEmpty($courses, 'CoursesDataSet::fetchAllCourses() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(Course::class, $courses, 'CoursesDataSet::fetchAllCourses() should return an array of Course objects');
    }

    public function testFetchCourseById()
    {
        $course = $this->coursesDataSet->fetchCourseById(2);
        $this->assertInstanceOf(Course::class, $course, 'CoursesDataSet::fetchCourseById() should return a Course object');
        $this->assertEquals(2, $course->getId(), 'CoursesDataSet::fetchCourseById() should return a Course object with the correct id');
        $this->assertEquals('Computer Science', $course->getCourseName(), 'CoursesDataSet::fetchCourseById() should return a Course object with the correct course name');
    }
}
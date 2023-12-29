<?php
use PHPUnit\Framework\TestCase;

require_once (BASE_PATH . 'models/Core/functions.php');

require_once (base_path('models/DataSets/UserTypesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class UserTypesDataSetTest extends TestCase
{
    protected $userTypesDataSet;

    protected function setUp(): void
    {
        $this->userTypesDataSet = new UserTypesDataSet();
    }

    protected function tearDown(): void
    {
        $this->userTypesDataSet = null;
    }

    public function testFetchAllUserTypes()
    {
        $userTypes = $this->userTypesDataSet->fetchAllUserTypes();
        $this->assertIsArray($userTypes, 'UserTypesDataSet::fetchAllUserTypes() should return an array');
        $this->assertNotEmpty($userTypes, 'UserTypesDataSet::fetchAllUserTypes() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(UserType::class, $userTypes, 'UserTypesDataSet::fetchAllUserTypes() should return an array of UserType objects');
    }

    public function testFetchUserTypeById()
    {
        $userType = $this->userTypesDataSet->fetchUserTypeById(1);
        $this->assertInstanceOf(UserType::class, $userType, 'UserTypesDataSet::fetchUserTypeById() should return a UserType object');
        $this->assertEquals(1, $userType->getId(), 'UserTypesDataSet::fetchUserTypeById() should return a UserType object with the correct id');
        $this->assertEquals('Student', $userType->getType(), 'UserTypesDataSet::fetchUserTypeById() should return a UserType object with the correct user type');
    }

}
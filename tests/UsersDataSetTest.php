<?php
use PHPUnit\Framework\TestCase;

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/UsersDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class UsersDataSetTest extends TestCase
{
    protected $usersDataSet;

    protected function setUp(): void
    {
        $this->usersDataSet = new UsersDataSet();
    }

    protected function tearDown(): void
    {
        $this->usersDataSet = null;
    }

    public function testFetchAllUsers()
    {
        $users = $this->usersDataSet->fetchAllUsers();
        $this->assertIsArray($users, 'UsersDataSet::fetchAllUsers should return an array');
        $this->assertNotEmpty($users, 'UsersDataSet::fetchAllUsers should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(User::class, $users, 'UsersDataSet::fetchAllUsers should return an array of User objects');
    }

    public function testEmailMatch()
    {
        $user = $this->usersDataSet->emailMatch('test@asda.com');
        $this->assertTrue($user, 'UsersDataSet::emailMatch should return true');
    }

    public function testPhoneMatch()
    {
        $user = $this->usersDataSet->phoneMatch('0161999998');
        $this->assertTrue($user, 'UsersDataSet::phoneMatch should return true');
    }

    public function testGetUserDetails()
    {
        $user = $this->usersDataSet->getUserDetails('test@asda.com');
        $this->assertEquals(2, $user['id'], 'UsersDataSet::getUserDetails should return the correct id');
        $this->assertEquals(2, $user['userType'], 'UsersDataSet::getUserDetails should return the correct user type');
        $this->assertEquals(2, $user['companyData'], 'UsersDataSet::getUserDetails should return the correct company data id');
        $this->assertEquals(null, $user['studentData'], 'UsersDataSet::getUserDetails should return null for student data: test@asda.com');
        $this->assertEquals('test@asda.com', $user['email'], 'UsersDataSet::getUserDetails should return the correct email');
        $this->assertEquals('0161999998', $user['phoneNumber'], 'UsersDataSet::getUserDetails should return the correct phone number');
    }
}
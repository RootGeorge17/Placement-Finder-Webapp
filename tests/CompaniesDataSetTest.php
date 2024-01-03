<?php

use PHPUnit\Framework\TestCase;

const BASE_PATH = __DIR__ . '/../';

require_once ( BASE_PATH . 'models/Core/Functions.php');

require_once (base_path('models/DataSets/CompaniesDataSet.php'));
require_once (base_path('vendor/bin/phpunit.phar'));

class CompaniesDataSetTest extends TestCase
{
    protected $companiesDataSet;

    protected function setUp(): void
    {
        $this->companiesDataSet = new CompaniesDataSet();
    }

    protected function tearDown(): void
    {
        $this->companiesDataSet = null;
    }

    public function testFetchAllCompanies()
    {
        $companies = $this->companiesDataSet->fetchAllCompanies();
        $this->assertIsArray($companies, 'CompaniesDataSet::fetchAllCompanies() should return an array');
        $this->assertNotEmpty($companies, 'CompaniesDataSet::fetchAllCompanies() should return a non-empty array');
        $this->assertContainsOnlyInstancesOf(Company::class, $companies, 'CompaniesDataSet::fetchAllCompanies() should return an array of Company objects');
    }

    public function testFetchCompanyName()
    {
        $companyName = $this->companiesDataSet->fetchCompanyName(1);
        $this->assertIsString($companyName, 'CompaniesDataSet::fetchCompanyName() should return a string');
        $this->assertNotEmpty($companyName, 'CompaniesDataSet::fetchCompanyName() should return a non-empty string');
        $this->assertEquals('Morrisons', $companyName, 'CompaniesDataSet::fetchCompanyName() should return the correct company name');
    }

    public function testFetchCompanyById()
    {
        $company = $this->companiesDataSet->fetchCompanyById(1);
        $this->assertInstanceOf(Company::class, $company, 'CompaniesDataSet::fetchCompanyById() should return a Company object');
        $this->assertEquals(1, $company->getId(), 'CompaniesDataSet::fetchCompanyById() should return a Company object with the correct id');
        $this->assertEquals('Morrisons', $company->getCompanyName(), 'CompaniesDataSet::fetchCompanyById() should return a Company object with the correct company name');
    }

}
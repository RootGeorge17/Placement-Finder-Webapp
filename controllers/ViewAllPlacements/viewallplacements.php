<?php

require base_path('models/DataSets/PlacementsDataSet.php');
require base_path('models/DataSets/CompaniesDataSet.php');
require base_path('models/DataSets/IndustriesDataSet.php');
require base_path('models/Extensions/PlacementHelpers.php');
require base_path('models/DataSets/CoursesDataSet.php');

$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$industriesDataSet = new IndustriesDataSet();
$placementHelpers = new PlacementHelpers();
$proficienciesDataSet = new ProficienciesDataSet();
$skillsDataSet = new SkillsDataSet();
$coursesDataSet = new CoursesDataSet();

if(!authenticated())
{
    header('location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 2)
{
    header("Location: /");
    exit();
}

$limit = 16; // default limit of deliveries per page
$page = 1; // default page number
if (isset($_GET['limit'])) {
    if ($_GET['limit'] >= 1 && $_GET['limit'] <= 32) {
        $limit = $_GET['limit'];
    }
    else {
        header("Location: /placements?page=1&limit=16");
    }
}

if (isset($_GET['page'])) {
    if ($_GET['page'] >= 1) {
        $page = $_GET['page'];
    }
    else {
        header("Location: /placements?page=1&limit=16");
    }
}

$start = ($page - 1) * $limit; // start index of placements to be displayed

$rowCount = $placementsDataSet->fetchRowCountAll();

$total = ceil($rowCount / $limit); // total number of pages

$queryParams = [
    'page' => $_GET['page'] ?? 1,
    'limit' => $_GET['limit'] ?? 16,
    'sort' => 'all',
];

if (isset($_GET['sort'])){
    if ($_GET['sort'] == 'all'){
        $queryParams['sort'] = 'all';
    }
    elseif ($_GET['sort'] == 'nameasc'){
        $queryParams['sort'] = 'nameasc';
    }
    elseif ($_GET['sort'] == 'namedesc'){
        $queryParams['sort'] = 'namedesc';
    }
    elseif ($_GET['sort'] == 'salaryasc'){
        $queryParams['sort'] = 'salaryasc';
    }
    elseif ($_GET['sort'] == 'salarydesc') {
        $queryParams['sort'] = 'salarydesc';
    }
    elseif ($_GET['sort'] == 'locationasc'){
        $queryParams['sort'] = 'locationasc';
    }
    elseif ($_GET['sort'] == 'locationdesc') {
        $queryParams['sort'] = 'locationdesc';
    }
    else {
        header("Location: /placements?page=1&limit=16&sort=all");
    }
}

$sort = $queryParams['sort'];

$allPlacements = null;

if (isset($_GET['location'])) {
    if ($_GET['location']) {
        $queryParams['location'] = $_GET['location'];
    }
}
if (isset($_GET['industry'])) {
    if ($_GET['industry']) {
        $queryParams['industry'] = $_GET['industry'];
    }
}

if (isset($_GET['course'])) {
    if ($_GET['course']) {
        $queryParams['course'] = $_GET['course'];
    }
}

if (isset($_GET['skill'])) {
    if ($_GET['skill']) {
        $queryParams['skill'] = $_GET['skill'];
    }
}

if (isset($_GET['filter'])){
    $allPlacements = $placementsDataSet->fetchAllByLimitAndSortAndFilter($start, $limit, $sort,
        $queryParams['location'], $queryParams['industry']);
}
 else {
    $allPlacements = $placementsDataSet->fetchAllByLimitAndSort($start, $limit, $sort);
}

$queryString = http_build_query($queryParams);

$currentUrl = 'placements?' . $queryString;


view('/ViewAllPlacements/viewallplacements.phtml', [
        'pageTitle' => 'All Placements',
        'companiesDataSet' => $companiesDataSet,
        'industriesDataSet' => $industriesDataSet,
        'skillsDataSet' => $skillsDataSet,
        'placementHelpers' => $placementHelpers,

        'allPlacements' => $allPlacements,
        'allProficiencies' => $proficienciesDataSet->fetchAllProficiencies(),
        'allLocation' => GeneratePlacementData::getLocations(),
        'allIndustries' => $industriesDataSet->fetchAllIndustries(),
        'allInstitutions' => GeneratePlacementData::getInstitutions(),
        'allCourses' => $coursesDataSet->fetchAllCourses(),

        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'sort' => $sort,
        'currentUrl' => $currentUrl,
        ]
);

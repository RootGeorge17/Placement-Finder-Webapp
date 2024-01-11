<?php

require base_path('models/DataSets/PlacementsDataSet.php');
require base_path('models/DataSets/CompaniesDataSet.php');
require base_path('models/DataSets/IndustriesDataSet.php');
require base_path('models/Extensions/PlacementHelpers.php');
require base_path('models/DataSets/CoursesDataSet.php');
require base_path('models/DataSets/StudentsDataSet.php');

$placementsDataSet = new PlacementsDataSet();
$companiesDataSet = new CompaniesDataSet();
$industriesDataSet = new IndustriesDataSet();
$placementHelpers = new PlacementHelpers();
$proficienciesDataSet = new ProficienciesDataSet();
$skillsDataSet = new SkillsDataSet();
$coursesDataSet = new CoursesDataSet();
$studentsDataSet = new StudentsDataSet();

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
    if ($_GET['limit'] >= 1 && $_GET['limit'] <= 32 && is_numeric($_GET['limit'])) {
        $limit = htmlspecialchars($_GET['limit']);
    }
    else {
        header("Location: /placements?page=1&limit=16");
    }
}

if (isset($_GET['page'])) {
    if ($_GET['page'] >= 1 && is_numeric($_GET['page'])) {
        $page = htmlspecialchars($_GET['page']);
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

$compatibility = null;
$allPlacements = null;

//region Sort
if (isset($_GET['sort'])){ // get sort query param like ?sort=nameasc
    $sort = $_GET['sort']; // get the sort value from the url
    $sort = match ($sort) { // check if the sort value is valid
        'nameasc' => 'nameasc',
        'namedesc' => 'namedesc',
        'salaryasc' => 'salaryasc',
        'salarydesc' => 'salarydesc',
        'locationasc' => 'locationasc',
        'locationdesc' => 'locationdesc',
        'compatibilityasc' => 'compatibilityasc', // i just put these here so that the dropdown menu can have the correct value
        'compatibilitydesc' => 'compatibilitydesc',
        default => 'all',
    };
    $queryParams['sort'] = $sort; // set the sort value in the query params

    $compatibility = match ($sort) { // check if the sort value is valid
        'compatibilityasc' => 'compatibilityasc',
        'compatibilitydesc' => 'compatibilitydesc',
        default => null,
    }; // check if the sort value is valid
}
//endregion

$sort = $queryParams['sort']; // sort value

//region Filters
if (isset($_GET['location'])) { // get location query param like ?location=London
    if ($_GET['location']) {
        $queryParams['location'] = $_GET['location'];
    }
}
if (isset($_GET['industry'])) { // get industry query param like ?industry=DevOps
    if ($_GET['industry']) {
        $queryParams['industry'] = $_GET['industry'];
    }
}
if (isset($_GET['course'])) { // get course query param like ?course=Computer+Science
    if ($_GET['course']) {
        $queryParams['course'] = $_GET['course'];
    }
}
if (isset($_GET['skill'])) { // get skill query param like ?skill=Programming
    if ($_GET['skill']) {
        $queryParams['skill'] = $_GET['skill'];
    }
}
if (isset($_GET['company'])) { // get proficiency query param like ?proficiency=Beginner
    if ($_GET['company']) {
        $queryParams['company'] = $_GET['company'];
    }
}
//endregion

/*
 * pseudocode for when compatibility query param is set
 * get all placements
 * if compatibility is not null then
 *     get all placements for students
 *     display placements with compatibility
 *     remove matched placements from all placements
 *     display remaining placements
 * else
 *     display all placements
 * */

#region compatibility check
if ($compatibility !== null){
    if ($compatibility == 'compatibilityasc' || $compatibility == 'compatibilitydesc') {
        if (isset($_GET['filter'])) {
            $allPlacements = $placementsDataSet->fetchAllByLimitAndSortAndFilter(
                $start,
                $limit,
                $sort,
                $queryParams['location'],
                $queryParams['industry'],
                $queryParams['company'],
            ); // get placements with location and industry filter
        } else {
            $allPlacements = $placementsDataSet->fetchAllByLimitAndSort($start, $limit, $sort); // get placements without location and industry filter
        }

        $studentData = $studentsDataSet->fetchStudentDataByUserId($_SESSION['user']['id']); // get student data
        $matchedPlacements = $placementHelpers->getPlacementMatchesNew(
            $studentData,
            $placementsDataSet->fetchAllPlacements(),
            $skillsDataSet->fetchAllSkills()
        );  // get matched placements

        if ($compatibility == 'compatibilitydesc') {
            $matchedPlacements = array_reverse($matchedPlacements); // reverse array so its bad -> good -> excellent
        }

        $orderedMatchedPlacements = [];
        foreach ($matchedPlacements as $matchedPlacement) {
            $orderedMatchedPlacements = array_merge($orderedMatchedPlacements, $matchedPlacement);
        } // merge matched placements into one array

        $allPlacements = array_filter($allPlacements, function ($placement) use ($orderedMatchedPlacements) {
            return !in_array($placement, $orderedMatchedPlacements);
        }); // remove matched placements from all placements

        $allPlacements = array_values($allPlacements); // reindex array (remove empty indexes)
        $allPlacements = array_merge($orderedMatchedPlacements, $allPlacements); // merge ordered matched placements with remaining placements
        // sorry for the clusterfuck
     }
}
#endregion
else {
    if ($sort == 'compatibilityasc' || $sort == 'compatibilitydesc') { $sort = 'all'; }
    if (isset($_GET['filter'])) {
        $allPlacements = $placementsDataSet->fetchAllByLimitAndSortAndFilter($start, $limit, $sort,
            $queryParams['location'], $queryParams['industry'], $queryParams['company']); // get placements with location and industry filter
    } else {
        $allPlacements = $placementsDataSet->fetchAllByLimitAndSort($start, $limit, $sort); // get placements without location and industry filter
    }
}

$queryString = http_build_query($queryParams);

$currentUrl = 'placements?' . $queryString; // current url with query params, used for pagination links


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
        'allCompanies' => $companiesDataSet->fetchAllCompanies(),
        'allCourses' => $coursesDataSet->fetchAllCourses(),

        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'sort' => $sort,
        'currentUrl' => $currentUrl,
        ]
);

<?php

require base_path('models/DataSets/StudentsDataSet.php');
require base_path('models/DataSets/UsersDataSet.php');
require base_path('models/DataSets/CoursesDataSet.php');
require base_path('models/DataSets/IndustriesDataSet.php');


if (authenticated()) {
    if ($_SESSION['user']['usertype'] != 2) {
        header("Location: /");
        exit();
    }
}

$studentsDataSet = new StudentsDataSet();
$usersDataSet = new UsersDataSet();
$coursesDataSet = new CoursesDataSet();
$industriesDataSet = new IndustriesDataSet();

$limit = 16; // default limit of deliveries per page
$page = 1; // default page number
if (isset($_GET['limit'])) {
    if ($_GET['limit'] >= 1 && $_GET['limit'] <= 32) {
        $limit = $_GET['limit'];
    } else {
        header("Location: /placements?page=1&limit=16");
    }
}

if (isset($_GET['page'])) {
    if ($_GET['page'] >= 1) {
        $page = $_GET['page'];
    } else {
        header("Location: /placements?page=1&limit=16");
    }
}

$start = ($page - 1) * $limit; // start index of placements to be displayed

$rowCount = $studentsDataSet->fetchRowCountAll();

$total = ceil($rowCount / $limit); // total number of pages


view('/ViewAllStudents/viewallstudents.phtml', [
        'pageTitle' => 'All Students',
        'allStudents' => $studentsDataSet->fetchByLimit($start, $limit),
        'usersDataSet' => $usersDataSet,
        'coursesDataSet' => $coursesDataSet,
        'industriesDataSet' => $industriesDataSet,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
    ]
);

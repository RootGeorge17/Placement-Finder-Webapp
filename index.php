<?php
require_once('DataSets/CompaniesDataSets.php');

echo "<h1>mySQL DB connection test</h1>";


$coursesDataSet = new \DataSets\CompaniesDataSets();
$data = $coursesDataSet->fetchAllCompanies();

echo "<table border='1'>";
echo "<tr><td>ID</td><td>Name</td><td>address_1</td><td>address_2</td><td>postcode</td><td>delivererID</td><td>latitude</td><td>longitude</td><td>status</td><td>del_photo</td>";
foreach ($data as $deliveryPoint) {
    echo "<tr><td>" . $deliveryPoint->getId() . "</td><td>". $deliveryPoint->getCompanyName() . "</td>" . "</td><td>". $deliveryPoint->getEmail() . "</td>" . "</td><td>". $deliveryPoint->getPhoneNumber() . "</td>";
}

echo "</table>";

$dbHandle = null;
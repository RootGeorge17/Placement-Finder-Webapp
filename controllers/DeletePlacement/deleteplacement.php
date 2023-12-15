<?php

var_dump($_POST);

if(!authenticated()){
    header('Location: /login');
    exit();
}

if ($_SESSION['user']['usertype'] == 2) {
    if ($_POST['delete'] == "delete"){
        require_once base_path("models/DataSets/PlacementsDataSet.php");
        require_once base_path("models/DataSets/UsersDataSet.php");
        $placementsDataSet = new PlacementsDataSet();
        $usersDataSet = new UsersDataSet();

        $user = $usersDataSet->fetchUserById($_SESSION['user']['id']);

        $placementId = $_POST['placementId'];

        $deleted = $placementsDataSet->deletePlacement($user->getCompanyId(), $placementId);
        if (!$deleted){
            $error['deletePlacement'] = "Error deleting placement";
            return view('Dashboard/employerdashboard.phtml', [
                'errors' => $error,
                'pageTitle' => 'Employer Dashboard',
            ]);
        }

        header('Location: /dashboard');
        exit();
    }

}
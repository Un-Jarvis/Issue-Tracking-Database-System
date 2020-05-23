<?php

if (isset($_GET["pid"])) {
    $pid = $_GET["pid"];
} else {
    exit;
}

if (isset($_POST["add_lead"])) {
    require("../project/manage_project.php");
    require("../user/manage_user.php");
    require("../connect.php");

    // Resume the existing session
    session_start();

    // Connect to the database
    $connection = connect_db();

    // Clean the data collected in the <form>
    $l_name = clean_input($connection, $_POST, "l_name", 10);

    // Validate login information
    $uid = get_uid_by_uname($connection, $l_name);
    if (empty($l_name)) {
        header("Location: ../project/project.php?pid={$pid}&add_lead=true&new_lead=empty_info");
        exit;
    } else if (!user_exist($connection, $l_name)) {
        header("Location: ../project/project.php?pid={$pid}&add_lead=true&new_lead=not_exist");
        exit;
    } else if (lead_exist($connection, $uid, $pid)) {
        header("Location: ../project/project.php?pid={$pid}&add_lead=true&new_lead=exist");
        exit;
    } else {
        add_project_lead($connection, $uid, $pid);
    }
}

// Redirect to the projects page
header("Location: ../project/project.php?pid={$pid}");
exit;

?>

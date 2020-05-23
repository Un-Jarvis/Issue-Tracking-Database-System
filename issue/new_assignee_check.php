<?php

if (isset($_GET["iid"])) {
    $iid = $_GET["iid"];
} else {
    exit;
}

if (isset($_POST["assign"])) {
    require("../issue/manage_issue.php");
    require("../user/manage_user.php");
    require("../connect.php");

    // Resume the existing session
    session_start();

    // Connect to the database
    $connection = connect_db();

    // Clean the data collected in the <form>
    $a_name = clean_input($connection, $_POST, "a_name", 30);

    // Validate login information
    $uid = get_uid_by_uname($connection, $a_name);
    if (empty($a_name)) {
        header("Location: ../issue/issue.php?iid={$iid}&assign=true&assignee=empty_info");
        exit;
    } else if (!user_exist($connection, $a_name)) {
        header("Location: ../issue/issue.php?iid={$iid}&assign=true&assignee=not_exist");
        exit;
    } else if (assignee_exist($connection, $uid, $iid)) {
        header("Location: ../issue/issue.php?iid={$iid}&assign=true&assignee=exist");
        exit;
    } else {
        assign_to($connection, $iid, $uid);
    }
}

// Redirect to the projects page
header("Location: ../issue/issue.php?iid={$iid}");
exit;

?>

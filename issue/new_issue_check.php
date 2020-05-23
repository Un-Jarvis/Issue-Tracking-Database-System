<?php

if (isset($_GET["pid"])) {
    $pid = $_GET["pid"];
} else {
    exit;
}

if (isset($_POST["report_issue"])) {
    require("../issue/manage_issue.php");
    require("../user/manage_user.php");
    require("../connect.php");

    // Resume the existing session
    session_start();

    // Connect to the database
    $connection = connect_db();

    // Clean the data collected in the <form>
    $i_title = clean_input($connection, $_POST, "i_title", 30);
    $description = clean_input($connection, $_POST, "i_description", 500);

    // Validate login information
    if (empty($i_title) || empty($description)) {
        header("Location: ../project/project.php?pid={$pid}&add_issue=true&new_issue=empty_info");
        exit;
    } else {
        $iid = add_issue($connection, $i_title, $description, $pid);
        $uid = get_uid_by_uname($connection, $_SESSION["login_username"]);
        add_update_history($connection, $iid, $uid, 1);
    }
}

// Redirect to the projects page
header("Location: ../project/project.php?pid={$pid}");
exit;

?>

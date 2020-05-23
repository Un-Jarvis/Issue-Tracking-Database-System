<?php

if (isset($_GET["iid"])) {
    $iid = $_GET["iid"];
} else {
    exit;
}

if (isset($_POST["update_issue"])) {
    require("../user/manage_user.php");
    require("../issue/manage_issue.php");
    require("../project/manage_workflow.php");
    require("../connect.php");

    // Resume the existing session
    session_start();

    // Connect to the database
    $connection = connect_db();

    // Clean the data collected in the <form>
    $i_title = clean_input($connection, $_POST, "i_title", 30);
    $description = clean_input($connection, $_POST, "i_description", 500);
    $next_status = $_POST["i_status"];

    // Update if input is not empty
    if (!empty($i_title)) {
        update_issue_title($connection, $iid, $i_title);
    }
    if (!empty($description)) {
        update_issue_description($connection, $iid, $description);
    }
    if (!empty($next_status)) {
        // Get sid
        $sid = get_sid_by_status($connection, $next_status);
        // Get uid
        $uid = get_uid_by_uname($connection, $_SESSION["login_username"]);

        update_issue_status($connection, $iid, $sid);
        add_update_history($connection, $iid, $uid, $sid);
    }
}

// Redirect to the projects page
header("Location: ../issue/issue.php?iid={$iid}");
exit;

?>

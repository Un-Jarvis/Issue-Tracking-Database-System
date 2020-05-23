<?php

include("../user/manage_user.php");
include("../project/manage_project.php");
include("../issue/manage_issue.php");
include("../connect.php");

check_session();

// Resume the existing session
session_start();

// Connect to the database
$connection = connect_db();

// Get uid
$uid = get_uid_by_uname($connection, $_SESSION["login_username"]);

// Get pid
if (!isset($_GET["pid"])) {
    header("Location: ../user/projects.php");
    exit;
} else {
    $pid = $_GET["pid"];
}

if (isset($_POST["new_lead"])) {
    // Check if the current user is a lead
    if (lead_exist($connection, $uid, $pid)) {
        header("Location: ../project/project.php?pid={$pid}&add_lead=true");
    } else {
        header("Location: ../project/project.php?pid={$pid}&add_lead=permission_denied");
    }
    exit;
}

if (isset($_POST["edit"])) {
    // Get iid
    if (!isset($_GET["iid"])) {
        exit;
    } else {
        $iid = $_GET["iid"];
    }

    // Check if the current user is a lead, a reporter, or an assignee
    if (lead_exist($connection, $uid, $pid) || $uid == get_reporter_uid_by_iid($connection, $iid) || is_assignee($connection, $iid, $uid)) {
        header("Location: ../issue/issue.php?iid={$iid}&edit=true");
    } else {
        header("Location: ../issue/issue.php?iid={$iid}&edit=permission_denied");
    }
    exit;
}

if (isset($_POST["assign"])) {
    // Get iid
    if (!isset($_GET["iid"])) {
        exit;
    } else {
        $iid = $_GET["iid"];
    }

    // Check if the current user is a lead or a reporter
    if (lead_exist($connection, $uid, $pid)) {
        header("Location: ../issue/issue.php?iid={$iid}&assign=true");
    } else {
        header("Location: ../issue/issue.php?iid={$iid}&assign=permission_denied");
    }
    exit;
}

?>
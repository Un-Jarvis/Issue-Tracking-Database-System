<?php

require("../connect.php");

// Connect to the database
$connection = connect_db();

if (isset($_GET["pid"])) $pid = $_GET["pid"];

if (isset($_POST["search_issue"]) && isset($_POST["i_title"])) {
    // Clean the data collected in the <form>
    $i_title = clean_input($connection, $_POST, "i_title", 30);

    // Validate login information
    if (empty($i_title)) {
        header("Location: ../project/project.php?pid={$pid}&search=empty_info");
        exit;
    } else {
        header("Location: ../project/project.php?pid={$pid}&search_issue={$i_title}");
        exit;
    }
}

if (isset($_POST["advanced_search_issue"])) {
    $url_get = "";
    if (isset($_POST["contain_title"]) && !empty($_POST["contain_title"])) {
        $contain_title = clean_input($connection, $_POST, "contain_title", 30);
        $url_get .= "&title_contains={$contain_title}"; 
    }
    if (isset($_POST["issue_status"]) && !empty($_POST["issue_status"])) {
        $issue_status = clean_input($connection, $_POST, "issue_status", 10);
        $url_get .= "&search_status={$issue_status}"; 
    }
    if (isset($_POST["issue_reporter"]) && !empty($_POST["issue_reporter"])) {
        $issue_reporter = clean_input($connection, $_POST, "issue_reporter", 30);
        $url_get .= "&search_reporter={$issue_reporter}"; 
    }
    if (isset($_POST["issue_assignee"]) && !empty($_POST["issue_assignee"])) {
        $issue_assignee = clean_input($connection, $_POST, "issue_assignee", 30);
        $url_get .= "&search_assignee={$issue_assignee}"; 
    }
    header("Location: ../project/project.php?pid={$pid}&advanced_search_issue=true{$url_get}");
    exit;
}

header("Location: ../project/project.php?pid={$pid}");
exit;

?>

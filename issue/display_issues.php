<?php

include("../issue/manage_issue.php");

// If search button is not clicked, list all issues
if (!isset($_GET["search_issue"]) && !isset($_GET["advanced_search_issue"])) {
    // Formulate the SQL to find all issues of the project
    $query = "SELECT issue.iid, issue.title, issue.description, status.status
        FROM issue, project, status
        WHERE issue.pid = project.pid AND issue.sid = status.sid AND project.pid = ?";
} else if (isset($_GET["search_issue"])) {
    $i_title = $_GET["search_issue"];
    // Formulate the SQL to find searched issues of the project
    $query = "SELECT issue.iid, issue.title, issue.description, status.status
        FROM issue, project, status
        WHERE issue.pid = project.pid AND issue.sid = status.sid AND project.pid = ? AND issue.title = '{$i_title}'";
} else if (isset($_GET["advanced_search_issue"])) {
    $from = "issue NATURAL JOIN status";
    if (isset($_GET["title_contains"])) {
        $contain_title = $_GET["title_contains"];
        advanced_search_contain($connection, $pid, $contain_title);
        $from .= " NATURAL JOIN contain_title";
    }
    if (isset($_GET["search_status"])) {
        $issue_status = $_GET["search_status"];
        advanced_search_status($connection, $pid, $issue_status);
        $from .= " NATURAL JOIN search_status";
    }
    if (isset($_GET["search_reporter"])) {
        $issue_reporter = $_GET["search_reporter"];
        advanced_search_reporter($connection, $pid, $issue_reporter);
        $from .= " NATURAL JOIN search_reporter";
    }
    if (isset($_GET["search_assignee"])) {
        $issue_assignee = $_GET["search_assignee"];
        advanced_search_assignee($connection, $pid, $issue_assignee);
        $from .= " NATURAL JOIN search_assignee";
    }
    $query = "SELECT issue.iid, issue.title, issue.description, status.status
        FROM {$from} JOIN project ON issue.pid = project.pid
        WHERE issue.pid = ?";
}

// Execute the query
if ($stmt = $connection -> prepare($query)) {
    $stmt -> bind_param("i", $pid);
    $stmt -> execute();
    $stmt -> bind_result($iid, $i_title, $i_description, $status);

    // Print results in HTML
    echo "<table class='content'>\n";
    echo "<caption>Issue List</caption>\n";
    echo "<tr>\n";
    echo "<th style='width: 25%'>Title</th>";
    echo "<th>Description</th>\n";
    echo "<th style='width: 15%'>Status</th>\n";
    echo "</tr>\n";
    // List all projects of the user
    while ($stmt -> fetch()) {
        // Strip slashes
        $i_title = htmlspecialchars(stripslashes($i_title));
        $i_description = htmlspecialchars(stripslashes($i_description));

        echo "<tr>\n";
        echo "<td><a href='../issue/issue.php?iid=$iid'>{$i_title}</a></td>";
        echo "<td>{$i_description}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    // Close statement
    $stmt -> close();
} else {
    printf("Failed to prepare the SQL query!\n");
}

// Drop temporary tables
//drop_temp_tables($connection);

?>
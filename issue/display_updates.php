<?php

// Formulate the SQL to find all update records of the issue
$query = "SELECT U.displayname, UH.updatetime, S.status
    FROM update_history UH, status S, user U
    WHERE UH.sid = S.sid AND UH.updater = U.uid AND UH.iid = ?
    ORDER BY UH.updatetime DESC";

// Execute the query
if ($stmt = $connection -> prepare($query)) {
    $stmt -> bind_param("i", $iid);
    $stmt -> execute();
    $stmt -> bind_result($updater, $update_time, $status);

    // Print results in HTML
    echo "<table class='content'>\n";
    echo "<caption>Update History</caption>";
    echo "<tr>\n";
    echo "<th style='width: 40%'>Updater</th>";
    echo "<th style='width: 40%'>Status</th>\n";
    echo "<th>Update Time</th>\n";
    echo "</tr>\n";
    // List all projects of the user
    while ($stmt -> fetch()) {
        echo "<tr>\n";
        echo "<td>{$updater}</td>";
        echo "<td>{$status}</td>";
        echo "<td>{$update_time}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    // Close statement
    $stmt -> close();
} else {
    printf("Failed to prepare the SQL query!\n");
}

?>
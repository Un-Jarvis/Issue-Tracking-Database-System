<?php

function add_project($connection, $p_name, $description = NULL) {
    // Test the p_name parameter
    if (!isset($p_name)) return false;

    // Formulate the SQL to add the project
    $query = "INSERT INTO `project` VALUES (NULL, ?, ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ss", $p_name, $description);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Formulate the SQL to get the last inserted pid
    $query = "SELECT last_insert_id()";
    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> bind_result($pid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Return pid of the added project
    return $pid;
}

function add_project_lead($connection, $uid, $pid) {
    // Test the uid and pid parameter
    if (!isset($uid) || !isset($pid)) return false;

    // Formulate the SQL to add project_lead
    $query = "INSERT INTO `project_lead` VALUES (?, ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $uid, $pid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function lead_exist($connection, $uid, $pid) {
    // Test the uid and pid parameter
    if (!isset($uid) || !isset($pid)) return false;

    // Formulate the SQL to add project_lead
    $query = "SELECT *
        FROM project_lead
        WHERE `lead` = ? AND pid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $uid, $pid);
        $stmt -> execute();
        $stmt -> store_result();

        // If there is no row of result, then there does not exist a user with the same username
        if ($stmt -> num_rows() == 0)
            $exist = false;
        else
            $exist = true;

        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $exist;
}


function get_project_name_by_pid($connection, $pid) {
    // Test the pid parameter
    if (!isset($pid)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT pname FROM project WHERE pid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $pid);
        $stmt -> execute();
        $stmt -> bind_result($p_name);
        $stmt -> fetch();
        $p_name = htmlspecialchars(stripslashes($p_name));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $p_name;
}

function get_description_by_pid($connection, $pid) {
    // Test the pid parameter
    if (!isset($pid)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT description FROM project WHERE pid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $pid);
        $stmt -> execute();
        $stmt -> bind_result($description);
        $stmt -> fetch();
        $description = htmlspecialchars(stripslashes($description));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $description;
}

function get_leads_by_pid($connection, $pid) {
    // Test the pid parameter
    if (!isset($pid)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT displayname 
        FROM project_lead, user 
        WHERE project_lead.lead = user.uid AND project_lead.pid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $pid);
        $stmt -> execute();
        $stmt -> bind_result($lead);

        // Get all leads for the project
        $leads = "";
        while ($stmt -> fetch()) {
            $leads .= $lead . ", ";
        }
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return substr($leads, 0, strlen($leads) - 2);
}

?>
<?php

function add_status($connection, $status_arr) {
    // Formulate the SQL to add the status
    $query = "INSERT INTO `status` VALUES (NULL, ?)";

    foreach ($status_arr as $status) {
        // Add status if it does not exsit
        if (!status_exist($connection, $status)) {
            // Execute the query
            if ($stmt = $connection -> prepare($query)) {
                $stmt -> bind_param("s", $status);
                $stmt -> execute();
                // Close statement
                $stmt -> close();
            } else {
                printf("Failed to prepare the SQL query!\n");
            }
        }
    }
}

function add_transition($connection, $pid, $transition_arr) {
    // Formulate the SQL to add the transition
    $query = "INSERT INTO `transition` VALUES (?, ?, ?)";

    foreach ($transition_arr as $transition) {
        // Seperate from_status and to_status
        $statuses = explode("->", $transition);
        $from_sid = get_sid_by_status($connection, $statuses[0]);
        $to_sid = get_sid_by_status($connection, $statuses[1]);

        // Execute the query
        if ($stmt = $connection -> prepare($query)) {
            $stmt -> bind_param("iii", $pid, $from_sid, $to_sid);
            $stmt -> execute();
            // Close statement
            $stmt -> close();
        } else {
            printf("Failed to prepare the SQL query!\n");
        }
    }
}

function status_exist($connection, $status) {
    $exist = false;

    // Test the username parameter
    if (!isset($status)) return false;

    // Formulate the SQL to find the user
    $query = "SELECT sid FROM status WHERE status = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $status);
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

function valid_transition($connection, $transition_arr) {
    foreach ($transition_arr as $transition) {
        // Seperate from_status and to_status
        $statuses = explode("->", $transition);
        // Return false if any status in transition does not exist in database
        if (!status_exist($connection, $statuses[0]) || !status_exist($connection, $statuses[1])) return false;
    }
    return true;
}

function get_workflow_by_pid($connection, $pid) {
    // Test the pid parameter
    if (!isset($pid)) return false;
    
    // Formulate the SQL to get all transitions
    $query = "SELECT FromS.status, ToS.status
        FROM transition, status FromS, status ToS
        WHERE transition.fromsid = FromS.sid 
        AND transition.tosid = ToS.sid 
        AND transition.pid = ?;";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $pid);
        $stmt -> execute();
        $stmt -> bind_result($from_s, $to_s);

        // Get all transitions for the project
        $workflow = "";
        while ($stmt -> fetch()) {
            $workflow .= $from_s . " &#8594 " . $to_s . "; ";
        }

        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return substr($workflow, 0, strlen($workflow) - 2);
}

function get_sid_by_status($connection, $status) {
    // Formulate the SQL to find pid
    $query = "SELECT sid FROM status WHERE status = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $status);
        $stmt -> execute();
        $stmt -> bind_result($sid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $sid;
}

?>
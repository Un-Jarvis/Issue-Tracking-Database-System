<?php

function add_issue($connection, $i_title, $description, $pid, $sid = 1) {
    // Test the parameters
    if (!isset($i_title) || !isset($description) || !isset($pid)) return false;

    // Formulate the SQL to add the issue
    $query = "INSERT INTO `issue` VALUES (NULL, ?, ?, ?, ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ssii", $i_title, $description, $pid, $sid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Formulate the SQL to get the last inserted iid
    $query = "SELECT last_insert_id()";
    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> bind_result($iid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Return iid of the added issue
    return $iid;
}

function add_update_history($connection, $iid, $uid, $sid) {
    // Test the parameters
    if (!isset($iid) || !isset($uid) || !isset($sid)) return false;

    // Formulate the SQL to add the issue
    $query = "INSERT INTO `update_history` VALUES (?, ?, NOW(), ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("iii", $iid, $uid, $sid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function assignee_exist($connection, $uid, $iid) {
    // Test the uid and pid parameter
    if (!isset($uid) || !isset($iid)) return false;

    // Formulate the SQL to add project_lead
    $query = "SELECT *
        FROM assign_to
        WHERE assignee = ? AND iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $uid, $iid);
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

function get_issue_title_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "SELECT title FROM issue WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
        $stmt -> execute();
        $stmt -> bind_result($title);
        $stmt -> fetch();
        $title = htmlspecialchars(stripslashes($title));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $title;
}

function get_description_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "SELECT description FROM issue WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
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

function get_reporter_uid_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "WITH first_update AS (
            SELECT iid, min(updatetime) AS first_update
            FROM update_history
            WHERE iid = ?
            GROUP BY iid
        )
        SELECT updater
        FROM update_history NATURAL JOIN first_update
        WHERE updatetime = first_update AND iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $iid, $iid);
        $stmt -> execute();
        $stmt -> bind_result($reporter_uid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $reporter_uid;
}

function get_reporter_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    $reporter_uid = get_reporter_uid_by_iid($connection, $iid);

    $reporter = get_display_name_by_uid($connection, $reporter_uid);

    return $reporter;
}

function get_pid_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "SELECT pid FROM issue WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
        $stmt -> execute();
        $stmt -> bind_result($pid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $pid;
}

function get_current_status_by_iid($connection, $iid) {
    // Test the parameter
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "SELECT status FROM issue NATURAL JOIN status WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
        $stmt -> execute();
        $stmt -> bind_result($status);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $status;
}

function get_assignees_by_iid($connection, $iid) {
    // Test the pid parameter
    if (!isset($iid)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT user.displayname
        FROM issue, assign_to, user
        WHERE issue.iid = assign_to.iid AND assign_to.assignee = user.uid AND issue.iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
        $stmt -> execute();
        $stmt -> bind_result($assignee);

        // Get all leads for the project
        $assignees = "";
        while ($stmt -> fetch()) {
            $assignees .= $assignee . ", ";
        }
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return substr($assignees, 0, strlen($assignees) - 2);
}

function is_assignee($connection, $iid, $uid) {
    $is_assignee = false;

    // Test the pid parameter
    if (!isset($iid)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT user.uid
        FROM issue, assign_to, user
        WHERE issue.iid = assign_to.iid AND assign_to.assignee = user.uid AND issue.iid = ? AND user.uid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $iid, $uid);
        $stmt -> execute();
        $stmt -> store_result();
        if ($stmt -> num_rows() == 0)
            $is_assignee = false;
        else
            $is_assignee = true;
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $is_assignee;
}

function assign_to($connection, $iid, $uid) {
    // Test the parameters
    if (!isset($iid) || !isset($uid)) return false;

    // Formulate the SQL to add the issue
    $query = "INSERT INTO `assign_to` VALUES (?, ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $iid, $uid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function update_issue_title($connection, $iid, $title) {
    // Test the parameters
    if (!isset($iid) || !isset($title)) return false;

    // Formulate the SQL to add the issue
    $query = "UPDATE issue
        SET title = ?
        WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("si", $title, $iid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function update_issue_description($connection, $iid, $description) {
    // Test the parameters
    if (!isset($iid) || !isset($description)) return false;

    // Formulate the SQL to add the issue
    $query = "UPDATE issue
        SET description = ?
        WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("si", $description, $iid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function update_issue_status($connection, $iid, $sid) {
    // Test the parameters
    if (!isset($iid) || !isset($sid)) return false;

    // Formulate the SQL to add the issue
    $query = "UPDATE issue
        SET sid = ?
        WHERE iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $sid, $iid);
        $stmt -> execute();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function get_all_next_statuses($connection, $iid) {
    // Test the parameters
    if (!isset($iid)) return false;

    // Formulate the SQL to add the issue
    $query = "SELECT status
        FROM issue, transition, status
        WHERE issue.pid = transition.pid AND issue.sid = transition.fromsid 
            AND transition.tosid = status.sid AND iid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $iid);
        $stmt -> execute();
        $stmt -> bind_result($next_status);

        // Get all leads for the project
        $next_statuses = array();
        while ($stmt -> fetch()) {
            array_push($next_statuses, $next_status);
        }
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $next_statuses;
}

function advanced_search_contain($connection, $pid, $contain) {
    $contain = "%" . $contain . "%";

    $query = "CREATE TEMPORARY TABLE contain_title
        SELECT iid, title
        FROM issue, project
        WHERE issue.pid = project.pid AND project.pid = ? AND title LIKE ?";

    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("is", $pid, $contain);
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function advanced_search_status($connection, $pid, $status) {
    $query = "CREATE TEMPORARY TABLE search_status
        SELECT iid, status
        FROM issue, status
        WHERE issue.sid = status.sid AND pid = ? AND status = ?";

    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("is", $pid, $status);
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function advanced_search_reporter($connection, $pid, $reporter) {
    $query = "CREATE TEMPORARY TABLE search_reporter
        WITH first_update AS (
            SELECT iid, min(updatetime) AS first_update
            FROM update_history
            GROUP BY iid
        )
        SELECT update_history.iid, displayname AS reporter
        FROM issue, update_history, first_update, user
        WHERE issue.iid = update_history.iid AND update_history.iid = first_update.iid 
            AND updatetime = first_update AND updater = uid
            AND pid = ?
            AND uname = ?";

    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("is", $pid, $reporter);
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function advanced_search_assignee($connection, $pid, $assignee) {
    $uid = get_uid_by_uname($connection, $assignee);
    $query = "CREATE TEMPORARY TABLE search_assignee
        SELECT iid, assignee
        FROM issue NATURAL JOIN assign_to
        WHERE pid = ? AND assignee = ?";

    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ii", $pid, $uid);
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function drop_temp_tables($connection) {
    // Drop contain_title
    $query = "DROP TEMPORARY TABLE IF EXISTS contain_title";
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Drop search_status
    $query = "DROP TEMPORARY TABLE IF EXISTS search_status";
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Drop search_reporter
    $query = "DROP TEMPORARY TABLE IF EXISTS search_reporter";
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    // Drop search_assignee
    $query = "DROP TEMPORARY TABLE IF EXISTS search_assignee";
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> execute();
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

?>
<?php

if (isset($_POST["create_proj"])) {
    require("../project/manage_project.php");
    require("../project/manage_workflow.php");
    require("../user/manage_user.php");
    require("../connect.php");

    // Resume the existing session
    session_start();

    // Connect to the database
    $connection = connect_db();

    // Clean the data collected in the <form>
    $p_name = clean_input($connection, $_POST, "p_name", 30);
    $description = clean_input($connection, $_POST, "description", 500);
    $status_str = clean_input($connection, $_POST, "statuses", 500);
    $transition_str = clean_input($connection, $_POST, "transitions", 1000);

    // Validate new project information
    if (empty($p_name) || empty($status_str) || empty($transition_str)) {
        header("Location: ../user/projects.php?add_project=true&new_project=empty_info");
        exit;
    } else {
        // Validate status inputs
        $statuses = explode("; ", $status_str);
        if (count(array_unique($statuses)) < count($statuses)) {
            header("Location: ../user/projects.php?add_project=true&new_project=duplicate_status");
            exit;
        } 
        
        // Validate transition inputs
        $transitions = explode("; ", $transition_str);
        if (!valid_transition($connection, $transitions)) {
            header("Location: ../user/projects.php?add_project=true&new_project=invalid_workflow");
            exit;
        }

        // Add project
        if ($description == "") $pid = add_project($connection, $p_name);
        else $pid = add_project($connection, $p_name, $description);

        // The creator of the project will then automatically become the lead of the created project
        $uid = get_uid_by_uname($connection, $_SESSION["login_username"]);
        add_project_lead($connection, $uid, $pid);

        // Add workflow status
        add_status($connection, $statuses);

        // Add transitions
        add_transition($connection, $pid, $transitions);
    }
} 

// Redirect to the projects page
header("Location: ../user/projects.php");
exit;

?>

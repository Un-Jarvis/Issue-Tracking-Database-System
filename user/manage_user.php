<?php

function check_session() {
    // Resume the existing session
    session_start();

    // Check if the user has logged in
    if (!isset($_SESSION["login_username"])) {
        // Redirect to the home åpage
        header("Location: ../app/login.php?login=no_session");
        exit;
    }

    // Check if the request is from a different IP address to previously
    if (!isset($_SESSION["login_IP"]) || ($_SESSION["login_IP"] != $_SERVER["REMOTE_ADDR"])) {
        header("Location: ../app/logout.php");
        exit;
    }
}

function authenticate_user($connection, $username, $password) {
    $authenticated = false;

    // Test the username and password parameters
    if (!isset($username) || !isset($password))
        return false;

    $encrypt_password = hash("sha256", $password);

    // Formulate the SQL to find the user
    $query = "SELECT password FROM user WHERE uname = ? AND password = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ss", $username, $encrypt_password);
        $stmt -> execute();
        $stmt -> store_result();

        // If there is exactly one row of result, then the user is found
        if ($stmt -> num_rows() == 1)
            $authenticated = true;
        else
            $authenticated = false;

        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }

    return $authenticated;
}

function user_exist($connection, $username) {
    $exist = false;

    // Test the username parameter
    if (!isset($username)) return false;

    // Formulate the SQL to find the user
    $query = "SELECT uname FROM user WHERE uname = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $username);
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

function email_exist($connection, $email) {
    $exist = false;

    // Test the username parameter
    if (!isset($email)) return false;

    // Formulate the SQL to find the user
    $query = "SELECT email FROM user WHERE email = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $email);
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

function add_user($connection, $email, $username, $display_name, $password) {
    // Formulate the SQL to add the user
    $query = "INSERT INTO `user` VALUES (NULL, ?, ?, ?, ?)";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("ssss", $email, $username, $display_name, $password);
        $stmt -> execute();

        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
}

function get_uid_by_uname($connection, $username) {
    // Test the username parameter
    if (!isset($username)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT uid FROM user WHERE uname = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $stmt -> bind_result($uid);
        $stmt -> fetch();
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $uid;
}

function get_email_by_uname($connection, $username) {
    // Test the username parameter
    if (!isset($username)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT email FROM user WHERE uname = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $stmt -> bind_result($email);
        $stmt -> fetch();
        $email = htmlspecialchars(stripslashes($email));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $email;
}

function get_display_name_by_uname($connection, $username) {
    // Test the username parameter
    if (!isset($username)) return false;
    
    // Formulate the SQL to find pid
    $query = "SELECT displayname FROM user WHERE uname = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $stmt -> bind_result($display_name);
        $stmt -> fetch();
        $display_name = htmlspecialchars(stripslashes($display_name));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $display_name;
}

function get_display_name_by_uid($connection, $uid) {
    // Test the uid parameter
    if (!isset($uid)) return false;

    // Formulate the SQL to find pid
    $query = "SELECT displayname FROM user WHERE uid = ?";

    // Execute the query
    if ($stmt = $connection -> prepare($query)) {
        $stmt -> bind_param("i", $uid);
        $stmt -> execute();
        $stmt -> bind_result($display_name);
        $stmt -> fetch();
        $display_name = htmlspecialchars(stripslashes($display_name));
        // Close statement
        $stmt -> close();
    } else {
        printf("Failed to prepare the SQL query!\n");
    }
    return $display_name;
}

?>

<?php

require("../user/manage_user.php");
require("../connect.php");

// Connect to the database
$connection = connect_db();

// Clean the data collected in the <form>
$login_username = clean_input($connection, $_POST, "login_username", 10);
$login_password = clean_input($connection, $_POST, "login_password", 16);

// Validate login information
if (empty($login_username) || empty($login_password)) {
    header("Location: ../app/login.php?login=empty_info");
    exit;
} else if (!user_exist($connection, $login_username)) {
    // Redirect to the logout page
    header("Location: ../app/login.php?login=user_not_exist");
    exit;
} else if (!authenticate_user($connection, $login_username, $login_password)) {
    // Redirect to the logout page
    header("Location: ../app/login.php?login=info_not_match&username={$login_username}");
    exit;
} else {
    // Start new session
    session_start(); 

    // Register the login_username
    $_SESSION["login_username"] = $login_username;

    // Register the IP address that started this session
    $_SESSION["login_IP"] = $_SERVER["REMOTE_ADDR"];

    // Redirect to the home page
    header("Location: ../user/home.php");
    exit;
}

?>

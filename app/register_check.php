<?php

require("../user/manage_user.php");
require("../connect.php");

// Connect to the database
$connection = connect_db();

// Username cannot contain special characters
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["register_username"])) {
    header("Location: ../app/register.php?register=invalid_username");
    exit;
} else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["register_display_name"])) {
    header("Location: ../app/register.php?register=invalid_displayname");
    exit;
}

// Clean the data collected in the <form>
$register_email = clean_input($connection, $_POST, "register_email", 20);
$register_username = clean_input($connection, $_POST, "register_username", 10);
$register_display_name = clean_input($connection, $_POST, "register_display_name", 10);
$register_password = clean_input($connection, $_POST, "register_password", 16);
$confirm_password = clean_input($connection, $_POST, "confirm_password", 16);

// Validate login information
if (empty($register_email) || empty($register_username) || empty($register_display_name) 
    || empty($register_password) || empty($confirm_password)) {
    header("Location: ../app/register.php?register=empty_info");
    exit;
} else if (!filter_var($register_email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../app/register.php?register=invalid_email");
    exit;
} else if ($register_password != $confirm_password) {
    header("Location: ../app/register.php?register=password_not_match");
    exit;
} else if (user_exist($connection, $register_username)) {
    header("Location: ../app/register.php?register=user_exists");
    exit;
} else if (email_exist($connection, $register_email)) {
    header("Location: ../app/register.php?register=email_exists");
    exit;
} else {
    // Encrypt password
    $encrypt_password = hash("sha256", $register_password);
    add_user($connection, $register_email, $register_username, $register_display_name, $encrypt_password);
    header("Location: ../app/login.php?register=success");
    exit;
}

?>

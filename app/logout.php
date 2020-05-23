<?php

// Resume the existing session
session_start();

// Destroy the session.
session_destroy();

// Redirect to the login page
header("Location: ../app/login.php");

?>

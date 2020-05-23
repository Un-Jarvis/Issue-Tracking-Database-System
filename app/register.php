<html>
<head>
    <title>Register</title>
    <link type="text/css" href="../style.css" rel="stylesheet" />
</head>
<body>

<h1 class="title">Register</h1>

<form class="info" method="POST" action="../app/register_check.php">
    <table>
        <tr>
            <td>Email</td>
            <td><input type="text" size="20" maxlength="20" name="register_email"></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><input type="text" size="20" maxlength="10" name="register_username"></td>
        </tr>
        <tr>
            <td>Display Name</td>
            <td><input type="text" size="20" maxlength="10" name="register_display_name"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" size="20" maxlength="16" name="register_password"></td>
        </tr>
        <tr>
            <td>Confirm Password</td>
            <td><input type="password" size="20" maxlength="16" name="confirm_password"></td>
        </tr>
    </table>

    <?php

    // Print message
    if (isset($_GET["register"])) {
        $register_check = $_GET["register"];
        if ($register_check == "empty_info") {
            echo "<p class='error center'>All fields cannot be empty!</p>";
        } else if ($register_check == "invalid_email") {
            echo "<p class='error center'>Email is not valid!</p>";
        } else if ($register_check == "password_not_match") {
            echo "<p class='error center'>Password does not match!</p>";
        } else if ($register_check == "user_exists") {
            echo "<p class='error center'>The username already exists!</p>";
        } else if ($register_check == "email_exists") {
            echo "<p class='error center'>The email has already been used!</p>";
        } else if ($register_check == "invalid_username") {
            echo "<p class='error center'>Username cannot contain special characters!</p>";
        } else if ($register_check == "invalid_displayname") {
            echo "<p class='error center'>Display name cannot contain special characters!</p>";
        }
    }

    ?>

    <input class="button" type="submit" value="Register" name="register">
</form>

<p class="center">Already have an account? <a href="../app/login.php">Log in</a> here!</p>

</body>
</html>

<html>
<head>
    <title>Login</title>
    <link type="text/css" href="../style.css" rel="stylesheet" />
</head>
<body>

<h1 class="title">Login</h1>

<form class="info" method="POST" action="../app/login_check.php">
    <table>
        <tr>
            <td>Username</td>
            
            <?php

            if (!isset($_GET["username"])) {
                echo "<td><input type='text' size='20' maxlength='10' name='login_username'></td>";
            } else {
                $username = $_GET["username"];
                echo "<td><input type='text' size='20' maxlength='10' name='login_username' value='$username'></td>";
            }

            ?>

        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" size="20" maxlength="16" name="login_password"></td>
        </tr>
    </table>


    <?php

    // Print message
    if (isset($_GET["login"])) {
        $login_check = $_GET["login"];
        if ($login_check == "empty_info") {
            echo "<p class='error center'>Username and password cannot be empty!</p>";
        } else if ($login_check == "user_not_exist") {
            echo "<p class='error center'>Username does not exist!</p>";
        } else if ($login_check == "info_not_match") {
            echo "<p class='error center'>Username and password do not match!</p>";
        } else if ($login_check == "no_session") {
            echo "<p class='error center'>Please login!</p>";
        } 
    }
    if (isset($_GET["register"])) {
        $register_check = $_GET["register"];
        if ($register_check == "success") {
            echo "<p class='success center'>New account has been registered!</p>";
        }
    }

    ?>

    <input class="button" type="submit" value="Log in" name="login">
</form>

<p class="center">Don’t have an account? <a href="../app/register.php">Sign up</a> here!</p>

</body>
</html>

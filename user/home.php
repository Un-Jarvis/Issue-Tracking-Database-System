<?php

include("../app/layout.php");
include("../user/manage_user.php");
include("../connect.php");

check_session();

// Resume the existing session
session_start();

// Connect to the database
$connection = connect_db();

// Get user's display name
$_SESSION["display_name"] = get_display_name_by_uname($connection, $_SESSION["login_username"]);

print_header("Home", "home");

?>

<div class="yield">

<?php

// Header
echo "<h1 class='title'>{$_SESSION["display_name"]}'s Home</h1>\n";

echo "<p><strong>Username: </strong>{$_SESSION['login_username']}</p>\n";

$email = get_email_by_uname($connection, $_SESSION['login_username']);
echo "<p><strong>Email: </strong>{$email}</p>\n";

?>

</div>

</body>

</html>
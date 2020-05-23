<?php

include("../app/layout.php");
include("../user/manage_user.php");
include("../connect.php");

check_session();

// Resume the existing session
session_start();

// Connect to the database
$connection = connect_db();

print_header("Projects", "projects");

?>

<div class="yield">

<?php

// Header
echo "<h1 class='title'>All Projects</h1>\n";

?>

<form method="POST" action="../user/projects.php?add_project=true">
    <input class="button" type="submit" value="Create a New Project" name="new_proj" style="width: 200px">
</form>

<?php

// Show add_project box
if (isset($_GET["add_project"]) && $_GET["add_project"] == "true") {
    include("../project/new_project.php");
}

// Formulate the SQL to find the all projects
$query = "SELECT pid, pname, description FROM project ORDER BY pname";

// Execute the query
if ($stmt = $connection -> prepare($query)) {
    $stmt -> execute();
    $stmt -> bind_result($pid, $p_name, $p_description);

    // Print results in HTML
    echo "<table class='content'>\n";
    echo "<tr>\n";
    echo "<th style='width: 25%'>Name</th>";
    echo "<th>Description</th>\n";
    echo "</tr>\n";
    // List all projects of the user
    while ($stmt -> fetch()) {
        // Strip slashes
        $p_name = htmlspecialchars(stripslashes($p_name));
        $p_description = htmlspecialchars(stripslashes($p_description));

        echo "<tr>\n";
        echo "<td><a href='../project/project.php?pid=$pid'>{$p_name}</a></td>";
        echo "<td>{$p_description}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    // Close statement
    $stmt -> close();
} else {
    printf("Failed to prepare the SQL query!\n");
}

?>

</div>

</body>

</html>
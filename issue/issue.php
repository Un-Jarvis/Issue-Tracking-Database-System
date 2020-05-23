<?php

include("../app/layout.php");
include("../user/manage_user.php");
include("../project/manage_project.php");
include("../project/manage_workflow.php");
include("../issue/manage_issue.php");
include("../connect.php");

check_session();

// Resume the existing session
session_start();

// Connect to the database
$connection = connect_db();

// Get iid
if (!isset($_GET["iid"])) {
    exit;
} else {
    $iid = $_GET["iid"];
}

// Get pid and project name
$pid = get_pid_by_iid($connection, $iid);
$p_name = get_project_name_by_pid($connection, $pid);

// Get issue name and description
$i_title = get_issue_title_by_iid($connection, $iid);
$i_description = get_description_by_iid($connection, $iid);

print_header("$i_title", "projects");

?>

<div class="yield">

<?php

// Header
echo "<h1 class='title'><a href='../project/project.php?pid={$pid}'>{$p_name}</a> - {$i_title}</h1>\n";

// Description
echo "<p><strong>Description: </strong>{$i_description}</p>\n";

// Reporter
$reporter = get_reporter_by_iid($connection, $iid);
echo "<p><strong>Reporter: </strong>{$reporter}</p>\n";

// Assignees
$assignees = get_assignees_by_iid($connection, $iid);
echo "<p><strong>Assignees: </strong>{$assignees}</p>\n";

// Current status
$status = get_current_status_by_iid($connection, $iid);
echo "<p><strong>Current Status: </strong>{$status}</p>\n";

// A row of buttons
echo "<div class='buttonBar'>\n";

// Button to edit the issue
echo "<form class='button' method='POST' action='../user/permission_check.php?pid={$pid}&iid={$iid}'>\n";
echo "<input class='button create' type='submit' value='Edit Issue' name='edit' style='width: 200px'>\n";
echo "</form>\n";

// Button to assign the issue to a user
echo "<form class='button' method='POST' action='../user/permission_check.php?pid={$pid}&iid={$iid}'>\n";
echo "<input class='button create' type='submit' value='Assign Issue' name='assign' style='width: 200px'>\n";
echo "</form>\n";

echo "</div>\n";

// Shwo edit box
if (isset($_GET["edit"])) {
    if ($_GET["edit"] == "permission_denied") echo "<p class='error center'>You don't have the permission to edit this issue!</p>";
    if ($_GET["edit"] == "true") include("../issue/edit_issue.php");
}

// Shwo assign box
if (isset($_GET["assign"])) {
    if ($_GET["assign"] == "permission_denied") echo "<p class='error center'>You don't have the permission to assign this issue!</p>";
    if ($_GET["assign"] == "true") include("../issue/new_assignee.php");
}

// Update history
include("../issue/display_updates.php");

?>

</div>

</body>

</html>
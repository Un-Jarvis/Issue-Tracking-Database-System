<?php

include("../app/layout.php");
include("../user/manage_user.php");
include("../project/manage_project.php");
include("../project/manage_workflow.php");
include("../connect.php");

check_session();

// Resume the existing session
session_start();

// Connect to the database
$connection = connect_db();

// Get pid
if (!isset($_GET["pid"])) {
    header("Location: ../user/projects.php");
    exit;
} else {
    $pid = $_GET["pid"];
}

// Get project name and description
$p_name = get_project_name_by_pid($connection, $pid);
$p_description = get_description_by_pid($connection, $pid);

print_header("$p_name", "projects");

?>

<div class="yield">

<?php

// Header
echo "<h1 class='title'>{$p_name}</h1>\n";

// Description
echo "<p><strong>Description: </strong>{$p_description}</p>\n";

// Leads
$leads = get_leads_by_pid($connection, $pid);
echo "<p><strong>Leads: </strong>{$leads}</p>\n";

// Workflow
$workflow = get_workflow_by_pid($connection, $pid);
if ($workflow == "") {
    $workflow = "Not set";
}
echo "<p><strong>Workflow Transitions: </strong>{$workflow}</p>\n";

// A row of buttons
echo "<div class='buttonBar'>\n";

// Button to add a new lead
echo "<form class='button' method='POST' action='../user/permission_check.php?pid={$pid}'>\n";
echo "<input class='button create' type='submit' value='Add a Project Lead' name='new_lead' style='width: 200px'>\n";
echo "</form>\n";

// Button to report a new issue
echo "<form class='button' method='POST' action='../project/project.php?pid={$pid}&add_issue=true'>\n";
echo "<input class='button create' type='submit' value='Report a New Issue' name='new_issue' style='width: 200px'>\n";
echo "</form>\n";

echo "</div>\n";

// Shwo add_lead box
if (isset($_GET["add_lead"])) {
    if ($_GET["add_lead"] == "permission_denied") echo "<p class='error center'>You don't have the permission to add a lead!</p>";
    else if ($_GET["add_lead"] == "true") include("../project/new_lead.php");
} 

// Shwo add_issue box
if (isset($_GET["add_issue"]) && $_GET["add_issue"] == "true") {
    include("../issue/new_issue.php");
}

// Issue filter and search
include("../issue/search_issue.php");

// Button to display all issues
echo "<div class='buttonBar'>\n";
echo "<form class='button' method='POST' action='../project/project.php?pid={$pid}'>\n";
echo "<input class='button create' type='submit' value='Display All Issues' name='display_all_issues' style='width: 200px'>\n";
echo "</form>\n";
echo "</div>\n";

// Display issues
include("../issue/display_issues.php");

?>

</div>

</body>

</html>
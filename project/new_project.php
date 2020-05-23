<div id="insert">
    <form method="POST" action="../project/new_project_check.php">
        <table class="create">
            <caption>Create a New Project</caption>
            <tr>
                <td style="width: 20%">Project Name</td>
                <td><input type="text" maxlength="30" name="p_name"></td>
            </tr>
            <tr>
                <td>Description (opt.)</td>
                <td><input type="text" maxlength="500" name="description"></td>
            </tr>
            <tr>
                <td>Workflow Statuses</td>
                <td><input type="text" maxlength="500" name="statuses"></td>
            </tr>
            <tr>
                <td>Workflow Transitions</td>
                <td><input type="text" maxlength="1000" name="transitions"></td>
            </tr>
        </table>

        <?php
    
        if (isset($_GET["new_project"]) && $_GET["new_project"] == "empty_info") {
            echo "<p class='error center'>Required fields cannot be empty!</p>";
        } else if (isset($_GET["new_project"]) && $_GET["new_project"] == "duplicate_status") {
            echo "<p class='error center'>There are duplicate statuses!</p>";
        } else if (isset($_GET["new_project"]) && $_GET["new_project"] == "invalid_workflow") {
            echo "<p class='error center'>Invalid inputs in workflow fields!</p>";
        }

        ?>

        <input class="button" type="submit" value="Create" name="create_proj">
        <input class="button" type="submit" value="Cancel" name="cancel">
    </form>
</div>
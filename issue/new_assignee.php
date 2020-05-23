<div id="insert">

    <?php
    
    if (isset($_GET["iid"])) {
        $pid = $_GET["iid"];
    } else {
        exit;
    }

    echo "<form method='POST' action='../issue/new_assignee_check.php?iid={$iid}'>";
    
    ?>

        <table class="create">
            <caption>Assign Issue</caption>
            <tr>
                <td style="width: 20%">Assignee Username</td>
                <td><input type="text" maxlength="30" name="a_name"></td>
            </tr>
        </table>

        <?php

        if (isset($_GET["assignee"]) && $_GET["assignee"] == "empty_info") {
            echo "<p class='error center'>Assignee username cannot be empty!</p>";
        } else if (isset($_GET["assignee"]) && $_GET["assignee"] == "not_exist") {
            echo "<p class='error center'>Assignee username does not exist!</p>";
        } else if (isset($_GET["assignee"]) && $_GET["assignee"] == "exist") {
            echo "<p class='error center'>The user is already an assignee for this issue!</p>";
        } 

        ?>

        <input class="button" type="submit" value="Assign" name="assign">
        <input class="button" type="submit" value="Cancel" name="cancel">
    </form>
</div>
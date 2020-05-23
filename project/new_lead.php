<div id="insert">

    <?php
    
    if (isset($_GET["pid"])) {
        $pid = $_GET["pid"];
    } else {
        exit;
    }
    
    echo "<form method='POST' action='../project/new_lead_check.php?pid={$pid}'>";
    
    ?>

        <table class="create">
            <caption>Add a Project Lead</caption>
            <tr>
                <td style="width: 20%">Lead Username</td>
                <td><input type="text" maxlength="30" name="l_name"></td>
            </tr>
        </table>

        <?php

        if (isset($_GET["new_lead"]) && $_GET["new_lead"] == "empty_info") {
            echo "<p class='error center'>Lead username cannot be empty!</p>";
        } else if (isset($_GET["new_lead"]) && $_GET["new_lead"] == "not_exist") {
            echo "<p class='error center'>Lead username does not exist!</p>";
        } else if (isset($_GET["new_lead"]) && $_GET["new_lead"] == "exist") {
            echo "<p class='error center'>The user is already a lead for this project!</p>";
        } 

        ?>

        <input class="button" type="submit" value="Add" name="add_lead">
        <input class="button" type="submit" value="Cancel" name="cancel">
    </form>
</div>
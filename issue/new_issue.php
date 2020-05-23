<div id="insert">

    <?php
    
    if (isset($_GET["pid"])) $pid = $_GET["pid"];
    echo "<form method='POST' action='../issue/new_issue_check.php?pid={$pid}'>";
    
    ?>

        <table class="create">
            <caption>Report a New Issue</caption>
            <tr>
                <td style="width: 20%">Issue Title</td>
                <td><input type="text" maxlength="30" name="i_title"></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><input type="text" maxlength="500" name="i_description"></td>
            </tr>
        </table>

        <?php

        if (isset($_GET["new_issue"]) && $_GET["new_issue"] == "empty_info") {
            echo "<p class='error center'>Issue name and description cannot be empty!</p>";
        }

        ?>

        <input class="button" type="submit" value="Report" name="report_issue">
        <input class="button" type="submit" value="Cancel" name="cancel">
    </form>
</div>
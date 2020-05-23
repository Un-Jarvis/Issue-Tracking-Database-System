<?php

if (isset($_GET["pid"])) $pid = $_GET["pid"];

echo "<form method='POST' action='../issue/search_issue_check.php?pid={$pid}' style='padding: 0px'>";

?>

    <table style="width: 100%">
        <caption>Search By Title</caption>
        <tr>
            <td style="width: 30%">Search issues with the exact title: </td>
            <td><input type="text" maxlength="30" name="i_title"></td>
            <td style="width: 10%"><input class="button" type="submit" value="Search Issue" name="search_issue" style="margin: 0px"></td>
        </tr>
    </table>

    <?php

    if (isset($_GET["search"]) && $_GET["search"] == "empty_info") {
        echo "<p class='error center'>Field cannot be empty!</p>";
    }

    ?>

</form>

<?php

if (isset($_GET["pid"])) $pid = $_GET["pid"];

echo "<form method='POST' action='../issue/search_issue_check.php?pid={$pid}' style='padding: 0px'>";

?>

    <table style="width: 100%">
        <caption>Advanced Search</caption>
        <tr>
            <td style="width: 15%">Title contains: </td>
            <td style="width: 35%"><input type="text" maxlength="30" name="contain_title"></td>
            <td style="width: 15%">Issue Status: </td>
            <td><input type="text" maxlength="30" name="issue_status"></td>
        </tr>
        <tr>
            <td style="width: 15%">Issue Reporter: </td>
            <td style="width: 35%"><input type="text" maxlength="30" name="issue_reporter"></td>
            <td style="width: 15%">Issue Assignee: </td>
            <td><input type="text" maxlength="30" name="issue_assignee"></td>
        </tr>
    </table>
    <td style="width: 10%"><input class="button" type="submit" value="Search Issue" name="advanced_search_issue" style="margin: 0px"></td>

    <?php

    if (isset($_GET["contain"]) && $_GET["contain"] == "empty_info") {
        echo "<p class='error center'>Field cannot be empty!</p>";
    }

    ?>

</form>
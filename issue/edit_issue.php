<div id="insert">

    <?php
    
    if (isset($_GET["iid"])) {
        $iid = $_GET["iid"];
    } else {
        exit;
    }
    
    echo "<form method='POST' action='../issue/edit_issue_check.php?iid={$iid}'>";
    
    ?>

        <table class="create">
            <caption>Edit Issue</caption>
            <tr>
                <td style="width: 20%">Edit Issue Title</td>
                <td><input type="text" maxlength="30" name="i_title"></td>
            </tr>
            <tr>
                <td>Edit Description</td>
                <td><input type="text" maxlength="500" name="i_description"></td>
            </tr>
            <tr>
                <td>Update Status</td>
                <td>
                    <select name="i_status">
                        <option value="" disabled selected>Select your updated status</option>

                        <?php

                        $next_statuses = get_all_next_statuses($connection, $iid);

                        foreach ($next_statuses as $next_status) {
                            echo "<option value='$next_status'>$next_status</option>";
                        }

                        ?>

                    </select>
                </td>
            </tr>
        </table>

        <input class="button" type="submit" value="Update" name="update_issue">
        <input class="button" type="submit" value="Cancel" name="cancel">
    </form>
</div>
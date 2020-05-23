<?php

function connect_db() {
    $connection = new mysqli("127.0.0.1", "root", "417167627", "issue_tracking");
    
    /* Check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit;
    }

    return $connection;
}

function clean_input($connection, $array, $index, $max_length) {
    if (isset($array["{$index}"])) {
        $input = substr($array["{$index}"], 0, $max_length);
        $input = $connection -> real_escape_string($input);
        return $input;
    }
    return NULL;
}

?>

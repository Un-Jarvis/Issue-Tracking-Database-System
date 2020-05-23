<?php

function print_header($title, $active = "none") {
    session_start();
    echo "
        <html>
        <head>
            <title>{$title}</title>
            <link type='text/css' href='../style.css' rel='stylesheet' />
        </head>
        <body>
        
        <div class='boxShadow header'>
            <nav class='topBar left'>";

    if ($active == "home") {
        echo "
                <a class='active' href='../user/home.php'>Home</a>
                <a href='../user/projects.php'>Projects</a>";
    } else if ($active == "projects") {
        echo "
                <a href='../user/home.php'>Home</a>
                <a class='active' href='../user/projects.php'>Projects</a>";
    } else {
        echo "
                <a href='../user/home.php'>Home</a>
                <a href='../user/projects.php'>Projects</a>";
    }

    echo "
            </nav>
            <nav class='topBar right'>
                <p>{$_SESSION['display_name']}</p>        
                <a href='../app/logout.php'>Logout</a>
            </nav>
        </div>";
}

?>
<?php
function dbConnect()
{
    $dbUser = "root";
    $dbPass = "awoo";
    $connection = new mysqli("localhost", $dbUser, $dbPass, "forum_db");
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}

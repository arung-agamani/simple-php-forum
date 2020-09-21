<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = false;
    $username = $_POST["username"];
    $password = $_POST["password"];
    switch ($username) {
        case 'ShirayukiHaruka':
            if ($password === 'Awoo') {
                $_SESSION["username"] = "ShirayukiHaruka";
                $success = true;
            }
            break;
        case 'ShirayukiAiri':
            if ($password === 'Airi') {
                $_SESSION["username"] = "ShirayukiAiri";
                $success = true;
            }

            break;
        default:
            # code...
            break;
    }
    if ($success) {
        header('Location: /wbd/index.php');
    } else {
        header('Location: /wbd/login.php');
    }
}

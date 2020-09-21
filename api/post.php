<?php
include("../lib/dbConnect.php");
include("../lib/post.php");
$conn = dbConnect();

header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST["message"]; // base64
    $username = $_POST["username"]; // normal string
    if ($_POST["id_parent"]) {
        reply_post($conn, $username, $message, $_POST["id_parent"]);
    } else {
        create_new_post($conn, $username, $message);
    }

    $obj = new ReturnObject(200, "Awooo" . $message);
    echo json_encode($obj);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET["id_parent"]) {
        $message = get_replies($conn, $_GET["id_parent"]);
        $obj = new ReturnObject(200, json_encode($message->fetch_all()));
        echo json_encode($obj);
    } else {
        echo json_encode(new ReturnObject(401, "Bad Request bro"));
    }
}

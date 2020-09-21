<?php
class ReturnObject
{
    public $status;
    public $message;
    public function __construct(int $status, string $message)
    {
        $this->message = $message;
        $this->status = $status;
    }
}
function create_new_post(mysqli $db_connection, string $username, string $message)
{
    $timestamp = date(time());
    $randomString = bin2hex(random_bytes(16));
    $retval = "";
    $retsta = 0;
    $sql = "INSERT INTO forum_db(id, username, message, id_parent) VALUES('" . $randomString . "','" . $username . "','" . $message . "','');";
    if ($db_connection->query($sql) === TRUE) {
        $retval = "Values Inserted";
        $retsta = 200;
    } else {
        $retval = "Something wrong while creating new post";
        $retsta = 500;
    }
    $retobj = new ReturnObject($retsta, $retval);
    return json_encode($retobj);
}
function reply_post(mysqli $db_connection, string $username, string $message, string $id_parent)
{
    $timestamp = date(time());
    $randomString = bin2hex(random_bytes(16));
    $retval = "";
    $retsta = 0;
    $sql = "INSERT INTO forum_db(id, username, message, id_parent) VALUES('" . $randomString . "','" . $username . "','" . $message . "','" . $id_parent . "');";
    if ($db_connection->query($sql) === TRUE) {
        $retval = "Values Inserted";
        $retsta = 200;
    } else {
        $retval = "Something wrong while replying post";
        $retsta = 500;
    }
    $retobj = new ReturnObject($retsta, $retval);
    return json_encode($retobj);
}
function get_posts(mysqli $db_connection)
{
    $query = "SELECT * FROM forum_db WHERE id_parent=''";
    $cursor = $db_connection->query($query);
    return $cursor;
}
function get_replies(mysqli $db_connection, string $id_parent)
{
    $query = "SELECT * FROM forum_db WHERE id_parent='" . $id_parent . "';";
    $cursor = $db_connection->query($query);
    return $cursor;
}

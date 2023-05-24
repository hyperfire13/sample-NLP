<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Authorization');
header('Content-Type: application/json;charset=utf-8');
require dirname(__DIR__) . '/includes/autoloader.php';

use Classes\Database as Database;
use Classes\Helpers as Helper;
use Models\Token as Token;

$headers = getallheaders();
$db = new Database();
$helper = new Helper();
$connection = $db->connect();
$tokenChecker = new Token($connection);

$userid = $helper->cleanNumber($_POST['userId']);
$token = $_POST['token'];

if ($tokenChecker->checkToken($userid, $token) === false) {
    $helper->response_now(null, null, [
        'status' => "failed",
    ]);
}

$command = 'SELECT id, first_name, last_name, username, user_level FROM users WHERE id = ?';
$statement = $connection->prepare($command);
$statement->bind_param('i', $userid);
$statement->bind_result(
    $id,
    $first_name,
    $last_name,
    $username,
    $user_level
);

$statement->execute();
$statement->store_result();
$total_count = $statement->num_rows;
$statement->fetch();

if ($total_count > 0) {
    $date_added = date("Y-m-d H:i:s");
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'level' => $user_level,
        'firstName' => $first_name,
        'lastName' => $last_name,
    ]);
} else {
    $helper->response_now($statement, $connection, [
        'status' => "failed",
    ]);
}
?>
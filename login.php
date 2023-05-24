<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Authorization');
header('Content-Type: application/json;charset=utf-8');
// require 'includes/autoloader.php';
// require realpath(__DIR__) . '/includes/autoloader.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/autoloader.php';
use Classes\Database as Database;
use Classes\Helpers as Helper;

$db = new Database();
$helper = new Helper();
$connection = $db->connect();

$username = $helper->cleanString($_POST['username']);
$password = $helper->cleanString($_POST['password']);

$command = 'SELECT id, first_name, last_name, username, password, user_level FROM users WHERE BINARY username = ?';
$statement = $connection->prepare($command);
$statement->bind_param('s', $username);
$statement->bind_result(
    $id,
    $first_name,
    $last_name,
    $username,
    $user_password,
    $user_level
);

$statement->execute();
$statement->store_result();
$total_count = $statement->num_rows;
$statement->fetch();

if ($total_count > 0) {
    if (password_verify($password, $user_password)) {
        $date_added = date("Y-m-d H:i:s");
        $token = password_hash($user_password, PASSWORD_DEFAULT);
        $_SESSION['token'] = $token;
        $_SESSION['level'] = $user_level;
        $command = 'UPDATE users SET token = ? WHERE username = ? AND id = ?';
        $statement = $connection->prepare($command);
        $statement->bind_param('ssi',
            $token,
            $username,
            $id
        );
        $statement->execute();
        // PROMPT FOR FAILED QUERY
        if ($statement->affected_rows !== 1) {
            $helper->response_now($statement, $connection,[
                'status' => "failed",
            ]);
        }
        $helper->response_now($statement, $connection, [
            'status' => "success",
            'level' => $user_level,
            'token' => $token,
            'firstName' => $first_name,
            'lastName' => $last_name,
            'userId' => $id,
        ]);
    } else {
        $helper->response_now($statement, $connection, [
            'status' => "unauthorized",
        ]);
    }
} else {
    $helper->response_now($statement, $connection, [
        'status' => "failed",
    ]);
}
?>
<?php
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization');
    header('Content-Type: application/json;charset=utf-8');

    require 'includes/autoloader.php';
    use Classes\Database as Database;
    use Classes\Helpers as Helper;
    $headers = getallheaders();

    $db = new Database();
    $helper = new Helper();
    $connection = $db->connect();

    $username = $helper->cleanString($_POST['username']);
    $password = $helper->cleanString($_POST['password']);
    $firstname = $helper->cleanString($_POST['firstname']);
    $lastname = $helper->cleanString($_POST['lastname']);
    $level = $helper->cleanNumber($_POST['level']);
    // check if username not taken
    $command = 'SELECT username FROM users WHERE BINARY username = ?';
        $statement = $connection->prepare($command);
        $statement->bind_param('s', $username);
        $statement->bind_result(
            $username
        );

    $statement->execute();
    $statement->store_result();
    $total_count = $statement->num_rows;
    $statement->fetch();

    if ($total_count === 0) {
        // QUERY TO INSERT PROVINCE
        $date_added = date("Y-m-d H:i:s");
        $password = password_hash($password, PASSWORD_DEFAULT);
        $command = 'INSERT INTO users(first_name, last_name, username, password, user_level, created_at) VALUES (?, ?, ?, ?, ?, ?)';
        $statement = $connection->prepare($command);
        $statement->bind_param('ssssis',
            $firstname,
            $lastname,
            $username,
            $password,
            $level,
            $date_added
        );
        $statement->execute();
        // PROMPT FOR FAILED QUERY
        if ($statement->affected_rows !== 1) {
            $helper->response_now($statement, $connection,[
                'status' => "failed",
            ]);
        }
    } else {
        $helper->response_now($statement, $connection,[
            'status' => "duplicate",
        ]);
    }
    $helper->response_now($statement, $connection,[
        'status' => "success",
    ]);
?>
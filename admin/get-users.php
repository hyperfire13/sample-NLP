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
    $sections = [];

    $userid = $helper->cleanNumber($_POST['userId']);
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }

    $command = 'SELECT id, CONCAT(first_name, " " , last_name), username, user_level FROM users WHERE deleted_at IS NULL';
    $statement = $connection->prepare($command);
    $statement->bind_result(
        $id,
        $fullname,
        $username,
        $level
    );

    $statement->execute();

    while ($statement->fetch()) {
        $sections[] = [
            'id' => $id,
            'name' => $fullname,
            'username' => $username,
            'level' => $level
        ];
    }
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'sections' => $sections,
    ]);
?>
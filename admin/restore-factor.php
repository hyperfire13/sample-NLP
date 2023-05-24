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
    $factorId = $helper->cleanNumber($_POST['factorId']);
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }
    $date_added = date("Y-m-d H:i:s");
    $command = 'UPDATE factors_intervention set deleted_at = null WHERE id = ?';
    $statement = $connection->prepare($command);
    $statement->bind_param('i',
        $factorId,
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
    ]);
?>
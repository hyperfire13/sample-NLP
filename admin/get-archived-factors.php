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
    $factors = [];

    $userid = $helper->cleanNumber($_POST['userId']);
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }

    $command = 'SELECT id, factor, intervention FROM factors_intervention WHERE deleted_at IS NOT NULL';
    $statement = $connection->prepare($command);
    $statement->bind_result(
        $id,
        $factor,
        $intervention
    );

    $statement->execute();

    while ($statement->fetch()) {
        $factors[] = [
            'id' => $id,
            'name' => $factor,
            'interventions' => $intervention
        ];
    }
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'factors' => $factors,
    ]);
?>
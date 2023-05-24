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
    $records = [];
    $existingFactors = [];
    $userid = $helper->cleanNumber($_POST['userId']);
    $selectedYear = $helper->cleanNumber($_POST['selectedYear']);
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }
    $command = 'SELECT id, year_id, factors FROM results WHERE deleted_at IS NULL AND year_id = ?';
    $statement = $connection->prepare($command);
    $statement->bind_param('i',
        $selectedYear
    );
    $statement->bind_result(
        $id,
        $yearId,
        $factors,
    );
    $statement->execute();
    $statement->fetch();
    $statement->close(); 
    if (empty($factors)) {
        $helper->response_now(null, $connection, [
            'status' => "success",
            'id' => $id,
            'results' => $existingFactors,
        ]);
    }
    $factors = json_decode($factors);
    for ($i=0; $i < sizeof($factors); $i++) {
        $idToFetch = $factors[$i]->id;
        $command = 'SELECT id, factor, intervention FROM factors_intervention WHERE deleted_at IS NULL AND id = ?';
        $statement = $connection->prepare($command);
        $statement->bind_param('i',
            $idToFetch
        );
        $statement->bind_result(
            $factorId,
            $factorName,
            $factorInterventions
        );
        $statement->execute();
        while ($statement->fetch()) {
            $existingFactors[] = [
                'id' => $factorId,
                'name' => $factorName,
                'interventions' => $factorInterventions
            ];
        }
    }
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'id' => $id,
        'results' => $existingFactors,
    ]);
?>
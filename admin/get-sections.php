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
    $schoolId = $helper->cleanNumber($_POST['schoolId']);
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }

    $command = 'SELECT id, section_name FROM sections WHERE deleted_at IS NULL AND school_id = ?';
    $statement = $connection->prepare($command);
    $statement->bind_param('i', $schoolId);
    $statement->bind_result(
        $id,
        $section_name,
    );

    $statement->execute();

    while ($statement->fetch()) {
        $sections[] = [
            'id' => $id,
            'name' => $section_name
        ];
    }
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'sections' => $sections,
    ]);
?>
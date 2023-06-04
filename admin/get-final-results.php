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
    $existingRecordFullFeatures = [];
    $existingRecordTwoFeatures = [];
    $existingRecordThreeFeatures = [];
    $userid = $helper->cleanNumber($_POST['userId']);
    $selectedYear = $helper->cleanNumber($_POST['selectedYear']);
    $selectedSchool = $helper->cleanNumber($_POST['selectedSchool']);
    $condition = ($selectedSchool == '0') ? " IS NOT NULL" : ' = ' . $selectedSchool;
    $token = $_POST['token'];
    $results = [];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }
    $command = 'SELECT results.id, title, results.year_id, results.school_id, results.section_id, schools.school_name, sections.section_name, results.category  FROM results LEFT JOIN schools ON results.school_id = schools.id LEFT JOIN sections ON results.section_id = sections.id WHERE results.deleted_at IS NULL AND results.year_id = ? AND results.school_id '. " " . $condition;
    $statement = $connection->prepare($command);
    $statement->bind_param('i',
        $selectedYear,
    );
    $statement->bind_result(
        $id,
        $title,
        $yearId,
        $schoolId,
        $sectionId,
        $schoolName,
        $sectionName,
        $category
    );
    $statement->execute();
    while($statement->fetch()) {
        $results[] = [
            'id' => $id,
            'title' => $title,
            'yearId' => $yearId,
            'schoolId' => $schoolId,
            'sectionId' => $sectionId,
            'schoolName' => $schoolName,
            'sectionName' => $sectionName,
            'category' => $category,
        ];
    }
    die(json_encode($results));
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'id' => $id,
    ]);
?>
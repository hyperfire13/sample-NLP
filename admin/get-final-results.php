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
    $token = $_POST['token'];

    if ($tokenChecker->checkToken($userid, $token) === false) {
        $helper->response_now(null, null, [
            'status' => "failed",
        ]);
    }
    $command = 'SELECT id, year_id, result_file_full_feature, result_file_two_feature, result_file_three_feature FROM results WHERE deleted_at IS NULL AND year_id = ?';
    $statement = $connection->prepare($command);
    $statement->bind_param('i',
        $selectedYear
    );
    $statement->bind_result(
        $id,
        $yearId,
        $resultFullFeatures,
        $resultTwoFeatures,
        $resultThreeFeatures
    );
    $statement->execute();
    $statement->fetch();
    $resultFile = empty($resultFile) ? "empty" : $resultFile;
    $fileName1 = $_SERVER['DOCUMENT_ROOT'] . "/upload/final/" . $resultFullFeatures;
    $fileName2 = $_SERVER['DOCUMENT_ROOT'] . "/upload/final/" . $resultTwoFeatures;
    $fileName3 = $_SERVER['DOCUMENT_ROOT'] . "/upload/final/" . $resultThreeFeatures;

    if (file_exists($fileName1) && file_exists($fileName2) && file_exists($fileName3)) {
        if (($handle = fopen($fileName1, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $existingRecordFullFeatures[] = $data;
            }
            fclose($handle);
        }
        if (($handle = fopen($fileName2, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $existingRecordTwoFeatures[] = $data;
            }
            fclose($handle);
        }
        if (($handle = fopen($fileName3, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $existingRecordThreeFeatures[] = $data;
            }
            fclose($handle);
        }
    } else {
        $helper->response_now(null, null, [
            'status' => "no_results_found",
        ]);
    }
    // $track = [
    //     'TVL' => 1,
    //     'ABM' => 2,
    //     'STEM' => 3,
    //     'TVl-HE' => 4,
    //     'others' => 5
    // ];
    // for ($i=1; $i < sizeof($existingRecord) ; $i++) {
    //     $existingRecord[$i][7] = isset($track[$existingRecord[$i][7]]) ? $existingRecord[$i][7]: 'Others';
    // }
    // unset($existingRecord[0]);
    // $existingRecord = array_values($existingRecord); // 'reindex' array
    $helper->response_now($statement, $connection, [
        'status' => "success",
        'id' => $id,
        'fullFeature' => $existingRecordFullFeatures,
        'twoFeature' => $existingRecordTwoFeatures,
        'threeFeature' => $existingRecordThreeFeatures,
    ]);
?>
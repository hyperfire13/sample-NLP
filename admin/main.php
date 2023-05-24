
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Authorization');

require dirname(__DIR__) . '/includes/autoloader.php';
include dirname(__DIR__) . '/vendor/autoload.php';

use Classes\Database as Database;
use Classes\Helpers as Helper;
use Models\Token as Token;

// Initialize and load PDF Parser library 
$config = new \Smalot\PdfParser\Config(); // fixes the presentation of extra spaces issue
$config->setHorizontalOffset(''); // fixes the presentation of extra spaces issue
$parser = new \Smalot\PdfParser\Parser([], $config); 

// $db = new Database();
// $helper = new Helper();
// $connection = $db->connect();
// $tokenChecker = new Token($connection);
// $userid = $helper->cleanNumber($_POST['userId']);
// $selectedYear = $helper->cleanNumber($_POST['selectedYear']);
// $token = $_POST['token'];
// $schoolYear = $_POST['selectedYear'];

// if ($tokenChecker->checkToken($userid, $token) === false) {
//     $helper->response_now(null, null, [
//         'status' => "failed",
//     ]);
// }

// move file
// $code = rand(10000,99999);
// $date_added = date("YmdHis");
// $fileExtension =  pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
// $newFileName = $code. '-' . $schoolYear . '-' . $date_added. '-' . 'basefile.';
// $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $newFileName . $fileExtension;
// if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
//     $helper->response_now(null, null, [
//         'status' => "failed_moving_file",
//     ]);
// }
// $newFileName = $newFileName . $fileExtension;
 
// Source PDF file to extract text 
$file = $_SERVER['DOCUMENT_ROOT'] . '/file-uploads/' . "Capsule-proposal-soc-stud.docx.pdf";

// Parse pdf file using Parser library 
$pdf = $parser->parseFile($file); 
 
// Extract text from PDF 
$textContent = $pdf->getText();

function clean($string) {
    return preg_replace("/[^a-zA-Z0-9\s!?.,\'\"]/", "", $string); // Removes special chars.
 }

// get the estimated text content of rationale
if (strpos($textContent, "Rationale") !== false) {
    $rationaleLimit =  4500;
    $startOfRationale = strpos($textContent, "Rationale") + 55;
    $textContent = (substr($textContent, $startOfRationale, strpos($textContent, "Objectives:") - $startOfRationale));
    $textContent = clean($textContent);
    // start python here
     $command_exec = 'python ../python-codes/final-nlp.py ' . escapeshellarg(json_encode($textContent));
    $result = shell_exec($command_exec);
    echo $result;
} else {
    $helper->response_now(null, null, [
        'status' => "rationale_not_found",
    ]);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Authorization');

require dirname(__DIR__) . '/includes/autoloader.php';
include dirname(__DIR__) . '/vendor/autoload.php';

// Initialize and load PDF Parser library 
$config = new \Smalot\PdfParser\Config(); // fixes the presentation of extra spaces issue
$config->setHorizontalOffset(''); // fixes the presentation of extra spaces issue
$parser = new \Smalot\PdfParser\Parser([], $config); 
 
// Source PDF file to extract text 
$file = $_SERVER['DOCUMENT_ROOT'] . '/file-uploads/' . "Capsule-proposal-soc-stud.docx.pdf";

// Parse pdf file using Parser library 
$pdf = $parser->parseFile($file); 
 
// Extract text from PDF 
$textContent = $pdf->getText();
// Add line break 
$textContent = nl2br($textContent);
?>
<p><?php echo $textContent; ?></p>

</body>
</html>
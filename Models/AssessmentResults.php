<?php
namespace Models;


use Classes\Helpers as Helper;

Class AssessmentResults {

    private $connection;
    private $helper;
    private $flag;

    public function __construct($db)
    {
        $this->connection = $db;
        $this->helper = new Helper();

    }
    // insert details of the created student assessment
    public function insertAssessmentResultDetails($details) {
      $command = 'INSERT INTO assessment_results(file_path, creator, subject_id, module_id, section_id) VALUES ( ?, ?, ?, ?, ?)';
      $statement = $this->connection->prepare($command);
      $statement->bind_param('siiii',
        $details['filePath'],
        $details['creatorId'],
        $details['selectedSubject'],
        $details['selectedModule'],
        $details['selectedSection']
      );

      $statement->execute();
      
      if ($statement->affected_rows === 0) {
          $flag = $this->helper::failed;
      } else {
          $flag = $this->helper::success;
      }
      
      // CLOSES STATEMENT
      $statement->close();
      
      // COMMITS THE QUERIES
      $this->connection->commit();
      $this->connection->close();

      return $flag;
    }
}
?>
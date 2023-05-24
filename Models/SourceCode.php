<?php
namespace Models;

Class SourceCode
{
    private $connection;
    private $id;
    private $fileName;
    private $displayName;
    private $refProgrammingLanguage;
    private $description;
    private $youtubeLink;
    private $totalRecords;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // get data
    public function getSourceCodes($page, $size, $class) {
        $result = [];
        $offset = ($page - 1) * $size;
        $query = 'SELECT sc.id, filename, display_name, description, youtube_link, ref_pl.name, (SELECT COUNT(*) FROM source_codes WHERE class = ?) FROM source_codes as sc LEFT JOIN ref_programming_languages as ref_pl ON sc.ref_programming_languages_id = ref_pl.id WHERE class = ? ORDER BY sc.created_at DESC LIMIT ?,?';

        $statement = $this->connection->prepare($query);
        $statement->bind_param('iiii', $class, $class, $offset, $size);
        $statement->bind_result(
            $this->id,
            $this->fileName,
            $this->displayName,
            $this->description,
            $this->youtubeLink,
            $this->refProgrammingLanguage,
            $this->totalRecords
        );
        $statement->execute();
        while ($statement->fetch()) {
            $result[] = [
                'id' => $this->id,
                'fileName' => $this->fileName,
                'displayName' => $this->displayName,
                'youtubeLink' => $this->youtubeLink,
                'programmingLanguage' => $this->refProgrammingLanguage,
                'description' => $this->description
            ];
        }
        $statement->close();
        $totalPages = ceil($this->totalRecords / $size);

        return json_encode([
            'status' => 'success', 
            'sourceCodes' => $result,
            'totalPages' => $totalPages
        ]);
    }
}
?>
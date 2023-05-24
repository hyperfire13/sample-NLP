<?php
namespace Models;

Class Token
{
    private $token;
    private $userId;
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // get data
    public function checkToken($userId, $token ) {
        $command = 'SELECT token FROM users WHERE token = ?';
        $statement = $this->connection->prepare($command);
        $statement->bind_param('s', $token);
        $statement->bind_result(
            $this->token
        );

        $statement->execute();
        $statement->store_result();
        $total_count = $statement->num_rows;
        $statement->fetch();

        if ($total_count === 1) {
            return true;
        } else {
            return false;
        }
    }
}
?>
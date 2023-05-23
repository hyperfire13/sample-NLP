<?php 
namespace Classes;
Class Database
{
    private $connection;

    public function connect()
    {
        $this->connection = new \mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
        if ($this->connection->connect_error) {
            echo('Failed to Connect!');
        }
        $this->connection->autocommit(FALSE);
        mysqli_set_charset($this->connection,'utf8');

        return $this->connection;
    }
}
?>

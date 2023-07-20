<?php
if (!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection
{

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;

    public $conn;

//  constructor of the class. automatically called when the object is created. initializes the DB connection.
    public function __construct()
    {
//      if the connection is not established before, set the database connection
        if (!isset($this->conn)) {
//          create new 'mysqli' object, representing the connection to the MySql DB. using the below credentials it establishes a connection
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

//          if the connection fails by an issue with the DB server
            if (!$this->conn) {
                echo 'Cannot connect to database server';
                exit;
            }
        }

    }

//    destructor method of the class. automatically called when the object is no longer referenced or when the script execution ends. Here it is used to close the DB connection.
    public function __destruct()
    {
        $this->conn->close();
    }
}

?>

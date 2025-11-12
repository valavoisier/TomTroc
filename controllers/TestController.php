<?php 

class TestController {

    public function index() {
        $database = Database::getInstance();
        $connection = $database->getConnection();
        if ($connection) {
            echo "Database connection successful.";
        } else {
            echo "Database connection failed.";
        }
    }

}

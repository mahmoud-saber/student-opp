<?php


include_once  './class/database_connection.php';

class Validation
{
    private $connection;

    public function __construct()
    {
        // Include the database connection file and create a new instance of the Database class
        $database = new Database();
        $this->connection = $database->getConnection();
    }
    // vatidate function
    public function validate($name, $email, $age, $password)
    {
        $errors = [];

        if (isset($_POST['submit'])) {
            // Validate name
            if (empty($name)) {
                $errors[] = "Name is required.";
            } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $errors[] = "Only letters and white space allowed in name.";
            }

            // Validate email
            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }


            // Validate password
            if (empty($password)) {
                $errors[] = "Password is required.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters.";
            }

            // Validate age
            if (empty($age)) {
                $errors[] = "Age is required.";
            } elseif (!filter_var($age, FILTER_VALIDATE_INT) || $age <= 0 || $age > 99) {
                $errors[] = "Age must be a number between 1 and 99.";
            }

            return $errors;
        }
    }
}
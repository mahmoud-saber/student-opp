<?php
include_once  './class/database_connection.php';
include_once  './class/validation.php';
// create class Students
// to handle the student data
class Students
{
    private $connection;
    public $name;
    public $email;
    public $password;
    public $age;

    // constructor to create a new instance of the Database class
    // and establish a connection to the database
    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    // function to create a new student
    public function create($name, $email, $password, $age)
    {
        $query = "INSERT INTO students (name, email, password, age) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $result = $stmt->execute([$name, $email, $hashedPassword, $age]);

        return $result;

        if ($result) {
            header("Location: index.php");
            exit;
        } else {
            echo 'Failed to create student';
        }
    }
    public function checkDuplicateEmail($email)
    {
        // 1. Prepared SQL query using a positional placeholder `?`
        $query = "SELECT * FROM students WHERE email = ?";

        // 2. Prepare the statement to avoid SQL injection
        $stmt = $this->connection->prepare($query);

        // 3. Execute the statement with the provided $email
        $stmt->execute([$email]);

        // 4. If there's at least one match, the email is already in use
        if ($stmt->rowCount() > 0) {
            return "Email already exists.";
        }

        // 5. Otherwise, it's unique
        return true;
    }
    // function to get all students
    public function getAll()
    {
        $query = "SELECT * FROM students ORDER BY id DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // function to get student by id
    public function getById($id)
    {
        $query = "SELECT * FROM students WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // function to update student by id
    public function update($id, $name, $email, $age)
    {
        $query = "UPDATE students SET name = ?, email = ?, age = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $result = $stmt->execute([$name, $email, $age, $id]);

        if ($result) {
            header("Location: index.php");
            exit;
        } else {
            echo 'Failed to update student';
        }
    }
    // function to delete student by id
    // redirect to index.php
    // after deleting the student
    public function deleteById($id)
    {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "Student deleted successfully.";
        } else {
            echo "Failed to delete student.";
        }

        header("Location: index.php");
        exit;
    }
}
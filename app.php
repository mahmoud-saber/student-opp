<?php

include_once  './class/database_connection.php';
include_once  './class/students.php';
include_once  './class/validation.php';
// create a new instance of the Database class
// and establish a connection to the database
$database = new Database();
$connection = $database->getConnection();
$students = new Students($connection);
$validation = new Validation();

// to get all students data
$stm = $students->getAll();
// to checkes the data get from server
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    // Validate the input data
    $errors = $validation->validate($name, $email, $age, $password);
    // Check for duplicate email
    $emailCheck = $students->checkDuplicateEmail($email);
    if ($emailCheck !== true) {
        $errors[] = $emailCheck; // "Email already exists."
    }
    if (empty($errors)) {
        // If there are no errors, create the student
        // and redirect to the index page
        $students->create($name, $email, $password, $age);
        header("Location: index.php");
        exit;
    } else {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color:red;'>$error</li>";
        }
        echo "</ul>";
    }
} else {
    // to checkes is id in url and delete the student data/
    if (isset($_GET['id'])) {
        $students->deleteById($_GET['id']);
    }
}
// to checkes is id in url and data get from server update the student data and redirect to the index page

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $this->students->update($name, $email, $age);
    header("Location: index.php");
    exit;
}

// create a new instance of the Database class
// and establish a connection to the database
$database = new Database();
$connection = $database->getConnection();
$validation = new Validation();
$students = new Students($connection);


$student = null;
$errors = [];
// to checkes is id in url and get the student data 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // to get the student data by id
    $student = $students->getById($id);
}
// to checkes is id in url and get the student data and update the student data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $errors = $validation->validate($name, $email, $age, $password);
    // to check for  error in the email then update the student data
    if (empty($errors)) {
        $students->update($id, $name, $email, $age);
        header("Location: index.php");
        exit;
    }
}
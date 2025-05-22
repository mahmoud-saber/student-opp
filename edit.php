    <?php
    include_once './class/database_connection.php';
    include_once './class/students.php';
    include_once './class/validation.php';
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
    ?>

    <?php if ($student): ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <title>Document</title>
    </head>

    <body>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>">
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?= htmlspecialchars($student['age']) ?>">
            </div>
            <button type="submit" name="update">Update</button>
        </form>
    </body>

    </html>
    <?php else: ?>
    <p style="color:red;">Student not found.</p>
    <?php endif; ?>

    <?php
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color:red;'>$error</li>";
        }
        echo "</ul>";
    }
    ?>
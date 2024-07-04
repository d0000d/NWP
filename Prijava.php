<?php

// Database configuration
$host = 'localhost'; // MySQL server host
$user = 'root'; // MySQL username (default is root)
$pass = ''; // MySQL password (default is empty)
$db = 'domagoj_NWP'; // MySQL database name

// Establish MySQLi database connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

print '
<h1>Prijava</h1>
<div id="signin">';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_action_']) && $_POST['_action_'] == 'TRUE') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM users";
    $query .= " WHERE username='" .  $username . "'";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // Set session variables for authenticated user
            $_SESSION['user']['valid'] = true;
            $_SESSION['user']['id'] = $row['id'];
            $_SESSION['user']['firstname'] = $row['firstname'];
            $_SESSION['user']['lastname'] = $row['lastname'];
            $_SESSION['message'] = '<p>Welcome, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';

            // Redirect to admin page (adjust menu=7 according to your application)
            header("Location: index.php?menu=7");
            exit;
        } else {
            // Bad username or password
            $_SESSION['message'] = '<p>Kriva lozinka!</p>';
            header("Location: index.php?menu=6");
            exit;
        }
    } else {
        // User not found
        $_SESSION['message'] = '<p>Korisnik nije pronađen!</p>';
        header("Location: index.php?menu=6");
        exit;
    }
}

// Display sign-in form if _action_ is not set or FALSE
print '
    <form action="" name="myForm" id="myForm" method="POST">
        <input type="hidden" id="_action_" name="_action_" value="TRUE">

        <label for="username">Korisničko ime:*</label>
        <input type="text" id="username" name="username" value="" pattern=".{5,10}" required>

        <label for="password">Lozinka:*</label>
        <input type="password" id="password" name="password" value="" pattern=".{4,}" required>

        <input type="submit" value="Submit">
    </form>
</div>';
?>

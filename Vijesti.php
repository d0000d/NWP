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

// Define $action if it's not set to prevent potential undefined index errors
$action = isset($_GET['action']) ? $_GET['action'] : '';

if (!empty($action)) {
    // Fetch single news item if action is set
    $query  = "SELECT * FROM news";
    $query .= " WHERE id=" . $mysqli->real_escape_string($action);
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '
        <div class="vijesti">
            <img src="news/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
            <h2>' . $row['title'] . '</h2>
            <p>'  . $row['description'] . '</p>
            <time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
            <hr>
        </div>';
    } else {
        echo '<p>News item not found.</p>';
    }
} else {
    // Fetch all news items if action is not set
    echo '<h1>Vijesti</h1>';
    $query  = "SELECT * FROM news";
    $query .= " WHERE archive='N'";
    $query .= " ORDER BY date DESC";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
            <div class="news">
                <img src="news/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
                <h2>' . $row['title'] . '</h2>';
                if(strlen($row['description']) > 300) {
                    echo substr(strip_tags($row['description']), 0, 300).'... <a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '">More</a>';
                } else {
                    echo strip_tags($row['description']);
                }
                echo '
                <time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
                <hr>
            </div>';
        }
    } else {
        echo '<p>No news items found.</p>';
    }
}

// Close connection
$mysqli->close();
?>

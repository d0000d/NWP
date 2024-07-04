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

// Update user profile
if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
    $query  = "UPDATE users SET firstname='" . $_POST['firstname'] . "', lastname='" . $_POST['lastname'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] . "', country='" . $_POST['country'] . "', archive='" . $_POST['archive'] . "'";
    $query .= " WHERE id=" . (int)$_POST['edit'];
    $query .= " LIMIT 1";
    $result = $mysqli->query($query);

    if ($result) {
        $_SESSION['message'] = '<p>You successfully changed user profile!</p>';
    } else {
        $_SESSION['message'] = '<p>Error updating user profile: ' . $mysqli->error . '</p>';
    }

    // Redirect
    header("Location: index.php?menu=7&action=1");
    exit;
}

// Delete user profile
if (isset($_GET['delete']) && $_GET['delete'] != '') {
    $query  = "DELETE FROM users";
    $query .= " WHERE id=" . (int)$_GET['delete'];
    $query .= " LIMIT 1";
    $result = $mysqli->query($query);

    if ($result) {
        $_SESSION['message'] = '<p>You successfully deleted user profile!</p>';
    } else {
        $_SESSION['message'] = '<p>Error deleting user profile: ' . $mysqli->error . '</p>';
    }

    // Redirect
    header("Location: index.php?menu=7&action=1");
    exit;
}

// Show user info
if (isset($_GET['id']) && $_GET['id'] != '') {
    $query  = "SELECT * FROM users";
    $query .= " WHERE id=" . (int)$_GET['id'];
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        print '
        <h2>User profile</h2>
        <p><b>First name:</b> ' . $row['firstname'] . '</p>
        <p><b>Last name:</b> ' . $row['lastname'] . '</p>
        <p><b>Username:</b> ' . $row['username'] . '</p>';

        $_query  = "SELECT * FROM countries";
        $_query .= " WHERE country_code='" . $row['country'] . "'";
        $_result = $mysqli->query($_query);
        $_row = $_result->fetch_assoc();

        print '
        <p><b>Country:</b> ' . $_row['country_name'] . '</p>
        <p><b>Date:</b> ' . pickerDateToMysql($row['date']) . '</p>
        <p><a href="index.php?menu=7&action=1">Back</a></p>';
    } else {
        $_SESSION['message'] = '<p>User not found!</p>';
        header("Location: index.php?menu=7&action=1");
        exit;
    }
}

// Edit user profile
else if (isset($_GET['edit']) && $_GET['edit'] != '') {
    $query  = "SELECT * FROM users";
    $query .= " WHERE id=" . (int)$_GET['edit'];
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $checked_archive = false;

        print '
        <h2>Edit user profile</h2>
        <form action="" id="registration_form" name="registration_form" method="POST">
            <input type="hidden" id="_action_" name="_action_" value="TRUE">
            <input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">

            <label for="fname">Ime *</label>
            <input type="text" id="fname" name="firstname" value="' . $row['firstname'] . '" placeholder="Your name.." required>

            <label for="lname">Prezime *</label>
            <input type="text" id="lname" name="lastname" value="' . $row['lastname'] . '" placeholder="Your last natme.." required>

            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Your e-mail.." required>

            <label for="username">Korisničko ime *<small>(Username must have min 5 and max 10 char)</small></label>
            <input type="text" id="username" name="username" value="' . $row['username'] . '" pattern=".{5,10}" placeholder="Username.." required><br>

            <label for="country">Država</label>
            <select name="country" id="country">
                <option value="">molimo odaberite</option>';
                // Select all countries from database webprog, table countries
                $_query  = "SELECT * FROM countries";
                $_result = $mysqli->query($_query);

                while ($_row = $_result->fetch_assoc()) {
                    print '<option value="' . $_row['country_code'] . '"';
                    if ($row['country'] == $_row['country_code']) { print ' selected'; }
                    print '>' . $_row['country_name'] . '</option>';
                }

        print '
            </select>

            <label for="archive">Archive:</label><br />
            <input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
            <input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO

            <hr>

            <input type="submit" value="Submit">
        </form>
        <p><a href="index.php?menu=7&action=1">Back</a></p>';
    } else {
        $_SESSION['message'] = '<p>User not found!</p>';
        header("Location: index.php?menu=7&action=1");
        exit;
    }
}

// List of users
else {
    print '
    <h2>List of users</h2>
    <div id="users">
        <table>
            <thead>
                <tr>
                    <th width="16"></th>
                    <th width="16"></th>
                    <th width="16"></th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>E mail</th>
                    <th>Država</th>
                    <th width="16"></th>
                </tr>
            </thead>
            <tbody>';

            $query  = "SELECT * FROM users";
            $result = $mysqli->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    print '
                    <tr>
                        <td><a href="index.php?menu=7&action=1&id=' . $row['id'] . '"><img src="img/user.png" alt="user"></a></td>
                        <td><a href="index.php?menu=7&action=1&edit=' . $row['id'] . '"><img src="img/edit.png" alt="edit"></a></td>
                        <td><a href="index.php?menu=7&action=1&delete=' . $row['id'] . '"><img src="img/delete.png" alt="delete"></a></td>
                        <td><strong>' . $row['firstname'] . '</strong></td>
                        <td><strong>' . $row['lastname'] . '</strong></td>
                        <td>' . $row['email'] . '</td>
                        <td>';

                        $_query  = "SELECT * FROM countries";
                        $_query .= " WHERE country_code='" . $row['country'] . "'";
                        $_result = $mysqli->query($_query);
                        $_row = $_result->fetch_assoc();

                        print $_row['country_name'] . '
                        </td>
                    </tr>';
                }
            } else {
                print '<tr><td colspan="8">Nisu pronađeni korisnici</td></tr>';
            }

    print '
            </tbody>
        </table>
    </div>';
}

// Close MySQL connection
$mysqli->close();

?>

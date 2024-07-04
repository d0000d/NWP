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
<h1>Obrazac registracije</h1>
<div id="register">';

if (!isset($_POST['_action_']) || $_POST['_action_'] == FALSE) {
    print '
    <form action="" id="registration_form" name="registration_form" method="POST">
        <input type="hidden" id="_action_" name="_action_" value="TRUE">
        
        <label for="fname">Ime *</label>
        <input type="text" id="fname" name="firstname" placeholder="Your name.." required>

        <label for="lname">Prezime *</label>
        <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>
            
        <label for="email">E-mail *</label>
        <input type="email" id="email" name="email" placeholder="Your e-mail.." required>
        
        <label for="username">Korisničko ime:* <small>(Username must have min 5 and max 10 char)</small></label>
        <input type="text" id="username" name="username" pattern=".{5,10}" placeholder="Username.." required><br>
                            
        <label for="password">Lozinka:* <small>(Password must have min 4 char)</small></label>
        <input type="password" id="password" name="password" placeholder="Password.." pattern=".{4,}" required>

        <label for="country">Država:</label>
        <select name="country" id="country">
            <option value="">Molimo odaberite</option>';

            // Fetch countries from database table 'countries'
            $query  = "SELECT * FROM countries";
            $result = $mysqli->query($query);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    print '<option value="' . $row['country_code'] . '">' . $row['country_name'] . '</option>';
                }
            }
        
    print '
        </select>

        <input type="submit" value="Submit">
    </form>';
} else if ($_POST['_action_'] == TRUE) {
    
    // Sanitize user inputs
    $firstname = $mysqli->real_escape_string($_POST['firstname']);
    $lastname = $mysqli->real_escape_string($_POST['lastname']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $country = $mysqli->real_escape_string($_POST['country']);

    // Check if user with email or username already exists
    $query  = "SELECT * FROM users";
    $query .= " WHERE email='" .  $email . "'";
    $query .= " OR username='" .  $username . "'";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<p>User with this email or username already exists!</p>';
    } else {
        // Hash the password
        $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // Insert new user into 'users' table
        $insert_query  = "INSERT INTO users (firstname, lastname, email, username, password, country)";
        $insert_query .= " VALUES ('$firstname', '$lastname', '$email', '$username', '$pass_hash', '$country')";

        if ($mysqli->query($insert_query) === TRUE) {
            // Registration successful
            echo '<p>' . ucfirst(strtolower($firstname)) . ' ' .  ucfirst(strtolower($lastname)) . ', thank you for registering!</p>
            <hr>';
        } else {
            // Error in query execution
            echo "Error: " . $insert_query . "<br>" . $mysqli->error;
        }
    }
}

print '
</div>';

// Close MySQL connection
$mysqli->close();

?>

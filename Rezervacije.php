<?php


// Funkcija za spajanje na bazu podataka
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

    
    return $conn;
}

// Provjeravamo je li korisnik poslao formu za rezervaciju
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    
    // Provjeravamo jesu li svi potrebni podaci poslani iz forme
    if (isset($_POST['pool_id'], $_POST['reservation_date'], $_POST['start_time'], $_POST['end_time'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'])) {
        
        // Spajamo se na bazu podataka
        $conn = connectDB();

        // Pripremamo podatke za unos u bazu
        $pool_id = $_POST['pool_id'];
        $reservation_date = $_POST['reservation_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        
        // Ovdje možete dodati dodatne provjere i validaciju podataka ako je potrebno
        
        // SQL upit za unos rezervacije
        $sql = "INSERT INTO reservations (pool_id, user_id, reservation_date, start_time, end_time, first_name, last_name, email, phone)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Pripremamo statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssss", $pool_id, $_SESSION['user']['id'], $reservation_date, $start_time, $end_time, $first_name, $last_name, $email, $phone);
        
        // Izvršavamo statement
        if ($stmt->execute()) {
            $_SESSION['message'] = '<div class="success">Uspješno ste rezervirali bazen!</div>';
        } else {
            $_SESSION['message'] = '<div class="error">Došlo je do greške prilikom rezervacije: ' . $stmt->error . '</div>';
        }
        
        // Zatvaramo statement i vezu s bazom
        $stmt->close();
        $conn->close();
        
    } else {
        $_SESSION['message'] = '<div class="error">Molimo ispunite sva polja za rezervaciju!</div>';
    }

    // Preusmjeravamo korisnika natrag na pocetnu stranicu ili gdje želite nakon obrade rezervacije
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervacija bazena</title>
    <link rel="stylesheet" href="style.css"> <!-- Ovdje ukljucite svoj CSS ako postoji -->
</head>
<body>
    <header>
        <!-- Header sadržaj, ako postoji -->
    </header>
    <main>
        <h1>Rezervacija bazena</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="pool_id">Odaberi bazen:</label>
            <select name="pool_id" id="pool_id">
                <option value="1">Bazen Svetice</option>
                <option value="2">Bazen Mladost</option>
				<option value="1">Bazen Šalata</option>
                <!-- Dodajte ostale bazene kao opcije -->
            </select><br><br>
            <label for="reservation_date">Datum rezervacije:</label>
            <input type="date" id="reservation_date" name="reservation_date" required><br><br>
            <label for="start_time">Pocetak rezervacije:</label>
            <input type="time" id="start_time" name="start_time" required><br><br>
            <label for="end_time">Kraj rezervacije:</label>
            <input type="time" id="end_time" name="end_time" required><br><br>
            <label for="first_name">Ime:</label>
            <input type="text" id="first_name" name="first_name" required><br><br>
            <label for="last_name">Prezime:</label>
            <input type="text" id="last_name" name="last_name" required><br><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="phone">Kontakt telefon:</label>
            <input type="text" id="phone" name="phone" required><br><br>
            <input type="submit" name="submit" value="Rezerviraj">
        </form>
    </main>
    <footer>
        <!-- Footer sadržaj, ako postoji -->
    </footer>
</body>
</html>

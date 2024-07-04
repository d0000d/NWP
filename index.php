<?php 
	# Stop Hacking attempt
	define('__APP__', TRUE);
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	# Start session
    session_start();
	
	# Database connection
	
	
	# Variables MUST BE INTEGERS
    if(isset($_GET['menu'])) { $menu   = (int)$_GET['menu']; }
	if(isset($_GET['action'])) { $action   = (int)$_GET['action']; }
	
	# Variables MUST BE STRINGS A-Z
    if(!isset($_POST['_action_']))  { $_POST['_action_'] = FALSE;  }
	
	if (!isset($menu)) { $menu = 1; }
	
	# Classes & Functions
    include_once("functions.php");
	

$user = 'root';
$pass = '';
$db = 'domagoj_NWP';

$db = new mysqli('localhost', $user, $pass, $db);
	
print '
<!DOCTYPE html>
<html>
	<head>
    
    
    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Title -->
    <title>Example page - PHP API</title>
</head>
<body>
	<header>
		<div'; if ($menu > 1) { print ' class="hero-subimage"'; } else { print ' class="hero-image"'; }  print '></div>
		<nav>';
			include("Izbornik.php");
		print '</nav>
	</header>
	<main>';
		if (isset($_SESSION['message'])) {
			print $_SESSION['message'];
			unset($_SESSION['message']);
		}
	
	# Homepage
	if (!isset($menu) || $menu == 1) { include("Početna.php"); }
	
	# News
	else if ($menu == 2) { include("Vijesti.php"); }
	
	# Contact
	else if ($menu == 3) { include("Kontakt.php"); }
	
	# About us
	else if ($menu == 4) { include("O nama.php"); }
	
	# Register
	else if ($menu == 5) { include("Registracija.php"); }
	
	# Signin
	else if ($menu == 6) { include("Prijava.php"); }
	
	# Admin webpage
	else if ($menu == 7) { include("Admin.php"); }
	
	# Admin webpage
	else if ($menu == 8) { include("Rezervacije.php"); }

	
	print '
	</main>
	<footer>
		<p>Copyright &copy; ' . date("Y") . ' Domagoj Švec</p>
	</footer>
</body>
</html>';
?>

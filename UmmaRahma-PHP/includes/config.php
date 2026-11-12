<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'umma_rahma');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', 'http://localhost/umma-rahma');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die('Erreur de connexion à la base de données. Veuillez configurer includes/config.php');
}
?>

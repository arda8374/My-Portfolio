<?php
date_default_timezone_set('Europe/Istanbul');

try {
    $host = 'localhost';
    $dbname = 'dava_takip'; 
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo 'Bağlantı Hatası: ' . $e->getMessage();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['oturum'])) {
    $_SESSION['user'] = $_SESSION['oturum'];
    $_SESSION['giris_yapti'] = $_SESSION['oturum'];
    $_SESSION['user_name'] = $_SESSION['kullanici_adi'];
}
?>

<?php

ob_start();
session_start();

error_reporting(E_ALL); 
ini_set('display_errors', 1);
include("baglanti.php");

if (isset($_POST['giris'])) {
    
    $kullanici = trim($_POST['kullanici_adi']); 
    $sifre = trim($_POST['sifre']);


    $admin_sor = $db->prepare("SELECT * FROM admin WHERE kullanici_adi = :kadi");
    $admin_sor->execute(['kadi' => $kullanici]);
    $admin_oku = $admin_sor->fetch(PDO::FETCH_ASSOC);

    if ($admin_oku && $sifre == $admin_oku['sifre']) {
        $_SESSION['oturum'] = true;
        $_SESSION['id'] = $admin_oku['id']; 
        $_SESSION['kullanici_adi'] = $admin_oku['kullanici_adi'];
        $_SESSION['rol'] = 'admin';
        
        header("Location: yonetim_paneli.php"); 
        exit;
    }

  
    $uye_sor = $db->prepare("SELECT * FROM kullanicilar WHERE eposta = :eposta");
    $uye_sor->execute(['eposta' => $kullanici]);
    $uye_oku = $uye_sor->fetch(PDO::FETCH_ASSOC);

    if ($uye_oku && password_verify($sifre, $uye_oku['sifre'])) {
        $_SESSION['oturum'] = true;
        $_SESSION['id'] = $uye_oku['id']; 
        $_SESSION['kullanici_adi'] = $uye_oku['eposta']; 
        $_SESSION['rol'] = 'uye';
        
        header("Location: yonetim_paneli.php");
        exit;
    }
    
    $hata = "Kullanıcı adı veya şifre hatalı!";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>HukukSis Giriş</title>
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2c3e50; font-family: sans-serif; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 10px; width: 350px; text-align:center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight:bold; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .links { margin-top: 15px; font-size: 14px; }
        .links a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="color: #2c3e50; margin-bottom: 20px;">HukukSis Giriş</h2>
        <?php if(isset($hata)) { echo "<div class='error'>$hata</div>"; } ?>

        <form action="" method="post">
            <input type="text" name="kullanici_adi" placeholder="E-Posta veya Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit" name="giris">GİRİŞ YAP</button>
        </form>
        
        <div class="links">
            <a href="sifremi_unuttum.php">Şifremi Unuttum</a> | 
            <a href="kayit_ol.php">Kayıt Ol</a>
        </div>
    </div>
</body>
</html>
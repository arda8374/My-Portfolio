<?php
session_start();
require 'baglanti.php';


if (!isset($_SESSION['giris_yapti']) || $_SESSION['giris_yapti'] !== true) {
    header("Location: giris-sayfasi1.html");
    exit;
}

$mesaj = "";

if ($_POST) {
    $ad = $_POST['ad_soyad'];
    $tel = $_POST['telefon'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];

    if ($ad != "") {
        $sorgu = $db->prepare("INSERT INTO muvekkiller (ad_soyad, telefon, email, adres) VALUES (?, ?, ?, ?)");
        $ekle = $sorgu->execute([$ad, $tel, $email, $adres]);

        if ($ekle) {
            $mesaj = '<div style="color:green;">Müvekkil Başarıyla Eklendi!</div>';
        } else {
            $mesaj = '<div style="color:red;">Hata Oluştu!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müvekkil Ekle</title>
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
        body { background: #eee; }
        .form-kapsayici { width: 50%; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;}
        .btn { background: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%; }
        .geri-btn { background: #6c757d; text-decoration: none; color: white; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="form-kapsayici">
    <a href="yonetim_paneli.php" class="geri-btn">← Panele Dön</a>
    <h2>Yeni Müvekkil Ekle</h2>
    <?php echo $mesaj; ?>
    
    <form method="post">
        <label>Ad Soyad:</label>
        <input type="text" name="ad_soyad" required>

        <label>Telefon:</label>
        <input type="text" name="telefon">

        <label>E-Posta:</label>
        <input type="email" name="email">

        <label>Adres:</label>
        <textarea name="adres" rows="4"></textarea>

        <button type="submit" class="btn">Kaydet</button>
    </form>
</div>

</body>
</html>
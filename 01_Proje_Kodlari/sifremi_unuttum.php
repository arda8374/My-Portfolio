<?php

include("baglanti.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mesaj = "";

if (isset($_POST['btnSifreGonder'])) {
    
    $gelen_email = trim($_POST['email']); 

   
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE eposta = :eposta");
    $sorgu->execute(['eposta' => $gelen_email]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        
        $kullanici_adi = $kullanici['ad_soyad'];
        $kullanici_id  = $kullanici['id'];

        $yeni_sifre_acik = rand(100000, 999999);
        
       
        $yeni_sifre_hash = password_hash($yeni_sifre_acik, PASSWORD_DEFAULT);
        
        $guncelle = $db->prepare("UPDATE kullanicilar SET sifre = :sifre WHERE id = :id");
        $guncelle->execute(['sifre' => $yeni_sifre_hash, 'id' => $kullanici_id]);

        
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ozkula838@gmail.com'; 
            $mail->Password   = 'dhyl izxi uziq hvpn';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('ozkula838@gmail.com', 'Avukat Sistemi'); 
            $mail->addAddress($gelen_email); 

            $mail->isHTML(true);
            $mail->Subject = 'Sifre Sifirlama';
            $mail->Body    = "
                <h3>Sayın $kullanici_adi,</h3>
                <p>Şifre sıfırlama talebiniz üzerine hesabınıza geçici bir şifre atanmıştır.</p>
                <p>Yeni Şifreniz: <b style='font-size:20px; color:red;'>$yeni_sifre_acik</b></p>
                <p>Lütfen giriş yaptıktan sonra şifrenizi değiştirmeyi unutmayın.</p>
                <br>
                <a href='http://localhost/giris-sayfasi1.php'>Giriş Yapmak İçin Tıklayın</a>
            ";

            $mail->send();
            $mesaj = "<div style='color: green; font-weight:bold; text-align:center; padding:10px; border:1px solid green;'>✅ Yeni şifreniz ($gelen_email) adresine gönderildi!</div>";

        } catch (Exception $e) {
            $mesaj = "<div style='color: red;'>Mail Hatası: {$mail->ErrorInfo}</div>";
        }
    } else {
        $mesaj = "<div style='color: red; font-weight:bold; text-align:center;'>
                    ❌ Bu e-posta adresiyle kayıtlı üye bulunamadı!<br>
                    <small>Aranan tablo: kullanicilar</small>
                 </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifremi Unuttum</title>
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; font-family: sans-serif; }
        .kutu { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #218838; }
        a { text-decoration: none; color: #555; font-size: 14px; }
        a:hover { color: #000; }
    </style>
</head>
<body>
    <div class="kutu">
        <h2>🔒 Şifremi Unuttum</h2>
        <?php echo $mesaj; ?>
        <form method="post">
            <input type="email" name="email" required placeholder="Kayıtlı E-Posta Adresiniz">
            <button type="submit" name="btnSifreGonder">Yeni Şifre Gönder</button>
        </form>
        <br>
        <center><a href="giris-sayfasi1.php">Giriş Yap</a></center>
    </div>
</body>
</html>
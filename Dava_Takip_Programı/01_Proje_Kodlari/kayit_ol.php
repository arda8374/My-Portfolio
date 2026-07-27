<?php
include 'baglanti.php';
$mesaj = "";

if ($_POST) {
    $ad = $_POST['ad_soyad'];
    $eposta = $_POST['eposta'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT); 

    $kontrol = $db->prepare("SELECT id FROM kullanicilar WHERE eposta = ?");
    $kontrol->execute([$eposta]);
    
    if ($kontrol->rowCount() > 0) {
        $mesaj = "<div class='alert alert-danger'>Bu e-posta zaten kayıtlı!</div>";
    } else {
        $ekle = $db->prepare("INSERT INTO kullanicilar (ad_soyad, eposta, sifre) VALUES (?, ?, ?)");
        if ($ekle->execute([$ad, $eposta, $sifre])) {
            $mesaj = "<div class='alert alert-success'>Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...</div>";
            header("refresh:2; url=giris_final.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head><meta charset="UTF-8"><title>Kayıt Ol - HukukSis</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">
        <h3 class="text-center mb-3 text-primary">HukukSis Kayıt</h3>
        <?= $mesaj ?>
        <form method="POST">
            <div class="mb-3"><label>Ad Soyad</label><input type="text" name="ad_soyad" class="form-control" required></div>
            <div class="mb-3"><label>E-posta</label><input type="email" name="eposta" class="form-control" required></div>
            <div class="mb-3"><label>Şifre</label><input type="password" name="sifre" class="form-control" required></div>
            <button class="btn btn-primary w-100 fw-bold">KAYIT OL</button>
            <div class="text-center mt-3"><a href="giris_final.php" class="text-decoration-none">Giriş Yap</a></div>
        </form>
    </div>
</body>
</html>
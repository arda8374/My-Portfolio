<?php

session_start();
include 'baglanti.php';

if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}

$mesaj = "";
$kadi = $_SESSION['kullanici_adi'];
$rol = $_SESSION['rol'];

if (isset($_POST['guncelle'])) {
    $yeni_sifre = trim($_POST['yeni_sifre']);
    $yeni_sifre_tekrar = trim($_POST['yeni_sifre_tekrar']);

    if (empty($yeni_sifre) || strlen($yeni_sifre) < 4) {
        $mesaj = '<div class="alert alert-danger">Şifre en az 4 karakter olmalıdır!</div>';
    } elseif ($yeni_sifre != $yeni_sifre_tekrar) {
        $mesaj = '<div class="alert alert-danger">Şifreler birbiriyle eşleşmiyor!</div>';
    } else {
        if ($rol == 'admin') {
           
            $guncelle = $db->prepare("UPDATE admin SET sifre = ? WHERE kullanici_adi = ?");
            $sonuc = $guncelle->execute([$yeni_sifre, $kadi]);
        } else {
          
            $yeni_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $guncelle = $db->prepare("UPDATE kullanicilar SET sifre = ? WHERE eposta = ?");
            $sonuc = $guncelle->execute([$yeni_hash, $kadi]);
        }

        if ($sonuc) {
            $mesaj = '<div class="alert alert-success">Şifreniz başarıyla güncellendi!</div>';
        } else {
            $mesaj = '<div class="alert alert-danger">Bir hata oluştu!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir - HukukSis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
        body { background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { width: 100%; max-width: 400px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="card p-4">
    <h4 class="text-center mb-4">Şifre Güncelle</h4>
    <?= $mesaj ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="yeni_sifre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Yeni Şifre (Tekrar)</label>
            <input type="password" name="yeni_sifre_tekrar" class="form-control" required>
        </div>
        <button type="submit" name="guncelle" class="btn btn-primary w-100">Şifreyi Kaydet</button>
        <div class="text-center mt-3">
            <a href="profil.php" class="text-decoration-none text-secondary small">← Profilime Dön</a>
        </div>
    </form>
</div>

</body>
</html>
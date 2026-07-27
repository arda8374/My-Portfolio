<?php

session_start();
include 'baglanti.php';

if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}

$kadi = $_SESSION['kullanici_adi'];
$rol = $_SESSION['rol'];
$user_id = $_SESSION['id'];


if ($rol == 'admin') {
    $sorgu = $db->prepare("SELECT * FROM admin WHERE kullanici_adi = ?");
    $sorgu->execute([$kadi]);
    $user = $sorgu->fetch(PDO::FETCH_ASSOC);
} else {
    
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $sorgu->execute([$user_id]);
    $user = $sorgu->fetch(PDO::FETCH_ASSOC);
}

if (!$user) {
    $user = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim - HukukSis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="card mx-auto shadow" style="max-width: 500px; border-radius:15px;">
        <div class="card-body text-center">
            <a href="yonetim_paneli.php" class="btn btn-link text-decoration-none text-muted d-block text-start mb-3">← Panele Dön</a>
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px; height:80px; font-size:30px;">
                <?= strtoupper(substr($kadi, 0, 1)) ?>
            </div>
            <h3 class="fw-bold"><?= htmlspecialchars($user['ad_soyad'] ?? ($user['kullanici_adi'] ?? $kadi)) ?></h3>
            <p class="text-muted"><?= strtoupper($rol) ?> Hesabı</p>
            <hr>
            <div class="text-start">
                <p><strong>Kullanıcı:</strong> <?= htmlspecialchars($kadi) ?></p>
                <p><strong>Durum:</strong> <span class="badge bg-success">Aktif</span></p>
                <?php if($rol != 'admin'): ?>
                    <p><strong>Kayıt Tarihi:</strong> <?= htmlspecialchars($user['kayit_tarihi'] ?? 'Belirtilmemiş') ?></p>
                <?php endif; ?>
            </div>
            <a href="sifre_degistir.php" class="btn btn-primary w-100 mt-4">Şifremi Değiştir</a>
            <a href="cikis.php" class="btn btn-outline-danger w-100 mt-2">Güvenli Çıkış</a>
        </div>
    </div>
</body>
</html>
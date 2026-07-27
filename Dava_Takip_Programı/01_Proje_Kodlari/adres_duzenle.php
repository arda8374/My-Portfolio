<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['id']; 
$rol = $_SESSION['rol'];    


if ($rol == 'admin') {
   
    $k = $db->prepare("SELECT * FROM adres_defteri WHERE id=?"); 
    $k->execute([$id]); 
} else {
   
    $k = $db->prepare("SELECT * FROM adres_defteri WHERE id=? AND ekleyen_id=?"); 
    $k->execute([$id, $user_id]); 
}

$v = $k->fetch(PDO::FETCH_ASSOC);


if (!$v) {
    header("Location: yonetim_paneli.php?sayfa=adres");
    exit;
}

if ($_POST) {

    if ($rol == 'admin') {
        $guncelle = $db->prepare("UPDATE adres_defteri SET ad_soyad=?, kategori=?, telefon=?, email=? WHERE id=?");
        $guncelle->execute([$_POST['ad_soyad'], $_POST['kategori'], $_POST['telefon'], $_POST['email'], $id]);
    } else {
        $guncelle = $db->prepare("UPDATE adres_defteri SET ad_soyad=?, kategori=?, telefon=?, email=? WHERE id=? AND ekleyen_id=?");
        $guncelle->execute([$_POST['ad_soyad'], $_POST['kategori'], $_POST['telefon'], $_POST['email'], $id, $user_id]);
    }
    
    header("Location: yonetim_paneli.php?sayfa=adres");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kişi Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="card mx-auto shadow" style="max-width: 500px; border-radius: 12px;">
        <div class="card-body">
            <h4 class="mb-4">👤 Kişi Düzenle <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6">Admin Modu</span>' : '') ?></h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="ad_soyad" class="form-control" value="<?=htmlspecialchars($v['ad_soyad'])?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=htmlspecialchars($v['kategori'])?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="telefon" class="form-control" value="<?=htmlspecialchars($v['telefon'])?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($v['email'])?>">
                </div>
                <button class="btn btn-success w-100 fw-bold">KAYDET</button>
                <a href="yonetim_paneli.php?sayfa=adres" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">Vazgeç</a>
            </form>
        </div>
    </div>
</body>
</html>
<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}

$mesaj = "";
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['id']; 
$rol = $_SESSION['rol'];  


if ($rol == 'admin') {
    
    $sorgu = $db->prepare("SELECT * FROM kutuphane WHERE id = ?");
    $sorgu->execute([$id]);
} else {
   
    $sorgu = $db->prepare("SELECT * FROM kutuphane WHERE id = ? AND ekleyen_id = ?");
    $sorgu->execute([$id, $user_id]);
}

$kitap = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$kitap) { 
    header("Location: yonetim_paneli.php?sayfa=kutuphane");
    exit; 
}

if ($_POST) {
    $kitap_adi  = $_POST['kitap_adi'] ?? '';
    $yazar      = $_POST['yazar'] ?? '';
    $yayin_evi  = $_POST['yayin_evi'] ?? '';
    $basim_yili = $_POST['basim_yili'] ?? '';
    $durum      = $_POST['durum'] ?? 'Ofiste';
    $notlar     = $_POST['notlar'] ?? '';

    if (!empty($kitap_adi)) {
        
        if ($rol == 'admin') {
            $guncelle = $db->prepare("UPDATE kutuphane SET kitap_adi=?, yazar=?, yayin_evi=?, basim_yili=?, durum=?, notlar=? WHERE id=?");
            $basarili = $guncelle->execute([$kitap_adi, $yazar, $yayin_evi, $basim_yili, $durum, $notlar, $id]);
        } else {
            $guncelle = $db->prepare("UPDATE kutuphane SET kitap_adi=?, yazar=?, yayin_evi=?, basim_yili=?, durum=?, notlar=? WHERE id=? AND ekleyen_id=?");
            $basarili = $guncelle->execute([$kitap_adi, $yazar, $yayin_evi, $basim_yili, $durum, $notlar, $id, $user_id]);
        }

        if ($basarili) {
            $mesaj = '<div class="alert alert-success">✅ Kitap bilgileri güncellendi!</div>';
            header("refresh:2; url=yonetim_paneli.php?sayfa=kutuphane");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitap Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-5">
    <div class="card p-4 mx-auto shadow-lg" style="max-width: 600px; border-radius:15px;">
        <h3 class="mb-4 text-primary">
            <i class="fas fa-edit"></i> Kitap Düzenle 
            <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6 ms-2">Admin Yetkisi</span>' : '') ?>
        </h3>
        
        <?= $mesaj ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Kitap Adı</label>
                <input type="text" name="kitap_adi" class="form-control" value="<?= htmlspecialchars($kitap['kitap_adi']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Yazar</label>
                <input type="text" name="yazar" class="form-control" value="<?= htmlspecialchars($kitap['yazar']) ?>">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Yayın Evi</label>
                    <input type="text" name="yayin_evi" class="form-control" value="<?= htmlspecialchars($kitap['yayin_evi']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Basım Yılı</label>
                    <input type="text" name="basim_yili" class="form-control" maxlength="4" value="<?= htmlspecialchars($kitap['basim_yili']) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Durum</label>
                <select name="durum" class="form-select">
                    <option value="Ofiste" <?= $kitap['durum'] == 'Ofiste' ? 'selected' : '' ?>>Ofiste</option>
                    <option value="Ödünç Verildi" <?= $kitap['durum'] == 'Ödünç Verildi' ? 'selected' : '' ?>>Ödünç Verildi</option>
                    <option value="Kayıp" <?= $kitap['durum'] == 'Kayıp' ? 'selected' : '' ?>>Kayıp</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Notlar (Ödünç alan kişi vb.)</label>
                <textarea name="notlar" class="form-control" rows="3"><?= htmlspecialchars($kitap['notlar']) ?></textarea>
            </div>
            <button class="btn btn-primary w-100 mt-3"><i class="fas fa-save"></i> DEĞİŞİKLİKLERİ KAYDET</button>
            <a href="yonetim_paneli.php?sayfa=kutuphane" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">Vazgeç</a>
        </form>
    </div>
</body>
</html>
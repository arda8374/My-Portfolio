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
    $sorgu = $db->prepare("SELECT * FROM senetler WHERE id = ?");
    $sorgu->execute([$id]);
} else {
    $sorgu = $db->prepare("SELECT * FROM senetler WHERE id = ? AND ekleyen_id = ?");
    $sorgu->execute([$id, $user_id]);
}
$veri = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$veri) {
    header("Location: yonetim_paneli.php?sayfa=senet");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borclu   = $_POST['borclu'] ?? '';
    $alacakli = $_POST['alacakli'] ?? '';
    $tutar    = $_POST['tutar'] ?? 0;
    $vade     = $_POST['vade_tarihi'] ?? '';
    $durum    = $_POST['durum'] ?? 'Bekliyor';
    $notlar   = $_POST['notlar'] ?? '';

    if ($rol == 'admin') {
        
        $sql = "UPDATE senetler SET borclu=?, alacakli=?, tutar=?, vade_tarihi=?, durum=?, notlar=? WHERE id=?";
        $db->prepare($sql)->execute([$borclu, $alacakli, $tutar, $vade, $durum, $notlar, $id]);
    } else {
        
        $sql = "UPDATE senetler SET borclu=?, alacakli=?, tutar=?, vade_tarihi=?, durum=?, notlar=? WHERE id=? AND ekleyen_id=?";
        $db->prepare($sql)->execute([$borclu, $alacakli, $tutar, $vade, $durum, $notlar, $id, $user_id]);
    }
    
    header("Location: yonetim_paneli.php?sayfa=senet");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Senet Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="card mx-auto shadow" style="max-width: 600px; border-radius:15px;">
        <div class="card-body">
            <h4 class="text-danger mb-4">💸 Senet Bilgisi Düzenle <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6 ms-2">Admin Modu</span>' : '') ?></h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Borçlu</label>
                    <input type="text" name="borclu" class="form-control" value="<?= htmlspecialchars($veri['borclu']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alacaklı (Müvekkil)</label>
                    <input type="text" name="alacakli" class="form-control" value="<?= htmlspecialchars($veri['alacakli'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tutar (₺)</label>
                    <input type="number" step="0.01" name="tutar" class="form-control" value="<?= $veri['tutar'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Vade Tarihi</label>
                    <input type="date" name="vade_tarihi" class="form-control" value="<?= $veri['vade_tarihi'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Durum</label>
                    <select name="durum" class="form-select">
                        <option value="Bekliyor" <?=($veri['durum']=='Bekliyor'?'selected':'')?>>Bekliyor</option>
                        <option value="İcrada" <?=($veri['durum']=='İcrada'?'selected':'')?>>İcrada</option>
                        <option value="Tahsil Edildi" <?=($veri['durum']=='Tahsil Edildi'?'selected':'')?>>Tahsil Edildi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notlar</label>
                    <textarea name="notlar" class="form-control" rows="3"><?= htmlspecialchars($veri['notlar'] ?? '') ?></textarea>
                </div>
                <button class="btn btn-danger w-100 fw-bold py-2">DEĞİŞİKLİKLERİ KAYDET</button>
                <a href="yonetim_paneli.php?sayfa=senet" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">Vazgeç</a>
            </form>
        </div>
    </div>
</body>
</html>
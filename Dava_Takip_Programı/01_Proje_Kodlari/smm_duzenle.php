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
    $m = $db->prepare("SELECT * FROM smm_makbuzlar WHERE id=?"); 
    $m->execute([$id]); 
} else {
    $m = $db->prepare("SELECT * FROM smm_makbuzlar WHERE id=? AND ekleyen_id=?"); 
    $m->execute([$id, $user_id]); 
}
$d = $m->fetch(PDO::FETCH_ASSOC);


if (!$d) {
    header("Location: yonetim_paneli.php?sayfa=smm");
    exit;
}


if ($_POST) {
    $brut = $_POST['brut_tutar'] ?? 0;
    $stopaj = $_POST['stopaj_orani'] ?? 20;
    $kdv = $_POST['kdv_orani'] ?? 20;
    
   
    $net = ($brut - ($brut * ($stopaj/100))) + ($brut * ($kdv/100));
    
  
    if ($rol == 'admin') {
        $up = $db->prepare("UPDATE smm_makbuzlar SET muvekkil_adi=?, brut_tutar=?, net_alinan=?, tarih=?, aciklama=? WHERE id=?");
        $up->execute([$_POST['muvekkil_adi'], $brut, $net, $_POST['tarih'], $_POST['aciklama'], $id]);
    } else {
        $up = $db->prepare("UPDATE smm_makbuzlar SET muvekkil_adi=?, brut_tutar=?, net_alinan=?, tarih=?, aciklama=? WHERE id=? AND ekleyen_id=?");
        $up->execute([$_POST['muvekkil_adi'], $brut, $net, $_POST['tarih'], $_POST['aciklama'], $id, $user_id]);
    }
    
    header("Location: yonetim_paneli.php?sayfa=smm");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Makbuz Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="card mx-auto shadow" style="max-width: 600px; border-radius:15px;">
        <div class="card-body">
            <h4 class="text-success mb-4">📝 Makbuz Bilgilerini Güncelle <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6 ms-2">Admin Modu</span>' : '') ?></h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Müvekkil</label>
                    <input type="text" name="muvekkil_adi" class="form-control" value="<?= htmlspecialchars($d['muvekkil_adi']) ?>" required>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Brüt Tutar</label>
                        <input type="number" step="0.01" name="brut_tutar" class="form-control" value="<?= htmlspecialchars($d['brut_tutar']) ?>" required>
                    </div>
                    <div class="col-3">
                        <label class="form-label">Stopaj %</label>
                        <input type="number" name="stopaj_orani" class="form-control" value="20">
                    </div>
                    <div class="col-3">
                        <label class="form-label">KDV %</label>
                        <input type="number" name="kdv_orani" class="form-control" value="20">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tarih</label>
                    <input type="date" name="tarih" class="form-control" value="<?= htmlspecialchars($d['tarih']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="3"><?= htmlspecialchars($d['aciklama']) ?></textarea>
                </div>
                <button class="btn btn-success w-100 fw-bold py-2">MAKBUZU GÜNCELLE</button>
                <a href="yonetim_paneli.php?sayfa=smm" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">İptal</a>
            </form>
        </div>
    </div>
</body>
</html>
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
    
    $sorgu = $db->prepare("SELECT * FROM davalar WHERE id = ?");
    $sorgu->execute([$id]);
} else {
   
    $sorgu = $db->prepare("SELECT * FROM davalar WHERE id = ? AND ekleyen_id = ?");
    $sorgu->execute([$id, $user_id]);
}

$dava = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$dava) {
    header("Location: yonetim_paneli.php?sayfa=dosya_takip");
    exit;
}

if ($_POST) {
    
    if ($rol == 'admin') {
        $guncelle = $db->prepare("UPDATE davalar SET dosya_no=?, mahkeme=?, kategori=?, davaci=?, davali=?, durum=? WHERE id=?");
        $guncelle->execute([
            $_POST['dosya_no'], 
            $_POST['mahkeme'], 
            $_POST['kategori'] ?? $dava['kategori'], 
            $_POST['davaci'], 
            $_POST['davali'], 
            $_POST['durum'], 
            $id
        ]);
    } else {
       
        $guncelle = $db->prepare("UPDATE davalar SET dosya_no=?, mahkeme=?, kategori=?, davaci=?, davali=?, durum=? WHERE id=? AND ekleyen_id=?");
        $guncelle->execute([
            $_POST['dosya_no'], 
            $_POST['mahkeme'], 
            $_POST['kategori'] ?? $dava['kategori'], 
            $_POST['davaci'], 
            $_POST['davali'], 
            $_POST['durum'], 
            $id, 
            $user_id
        ]);
    }
    header("Location: yonetim_paneli.php?sayfa=dosya_takip");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dava Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="card mx-auto shadow" style="max-width: 700px; border-radius:15px;">
        <div class="card-body">
            <h4 class="mb-4 text-primary">⚖️ Dosya Bilgilerini Güncelle <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6 ms-2">Admin Yetkisi</span>' : '') ?></h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dosya No</label>
                        <input type="text" name="dosya_no" class="form-control" value="<?=htmlspecialchars($dava['dosya_no'])?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Açılış Tarihi</label>
                        <input type="date" class="form-control" value="<?=$dava['tarih']?>" disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-danger fw-bold form-label">Mahkeme</label>
                    <select name="mahkeme" class="form-select">
                        <?php 
                        $ms = $db->query("SELECT mahkeme_adi FROM tanim_mahkemeler ORDER BY mahkeme_adi ASC"); 
                        while($m=$ms->fetch(PDO::FETCH_ASSOC)){ 
                            $selected = ($m['mahkeme_adi'] == $dava['mahkeme']) ? 'selected' : '';
                            echo "<option {$selected}>".htmlspecialchars($m['mahkeme_adi'])."</option>"; 
                        } 
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Davacı</label>
                        <input type="text" name="davaci" class="form-control" value="<?=htmlspecialchars($dava['davaci'])?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Davalı</label>
                        <input type="text" name="davali" class="form-control" value="<?=htmlspecialchars($dava['davali'])?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="durum" class="form-select">
                        <option value="Açık" <?=($dava['durum']=='Açık'?'selected':'')?>>Açık</option>
                        <option value="Kapalı" <?=($dava['durum']=='Kapalı'?'selected':'')?>>Kapalı</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100 py-2 fw-bold">GÜNCELLE</button>
                <a href="yonetim_paneli.php?sayfa=dosya_takip" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">İptal</a>
            </form>
        </div>
    </div>
</body>
</html>
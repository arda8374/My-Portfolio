<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}

$mesaj = "";
if ($_POST) {
    $kitap_adi  = $_POST['kitap_adi'] ?? '';
    $yazar      = $_POST['yazar'] ?? '';
    $yayin_evi  = $_POST['yayin_evi'] ?? '';
    $basim_yili = $_POST['basim_yili'] ?? '';
    $durum      = $_POST['durum'] ?? 'Ofiste';
    $notlar     = $_POST['notlar'] ?? '';
    $user_id    = $_SESSION['id']; 

    if (!empty($kitap_adi)) {
       
        $ekle = $db->prepare("INSERT INTO kutuphane (ekleyen_id, kitap_adi, yazar, yayin_evi, basim_yili, durum, notlar) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($ekle->execute([$user_id, $kitap_adi, $yazar, $yayin_evi, $basim_yili, $durum, $notlar])) {
            $mesaj = '<div class="alert alert-success">Kitap başarıyla eklendi!</div>';
            header("refresh:2; url=yonetim_paneli.php?sayfa=kutuphane");
        }
    } else {
        $mesaj = '<div class="alert alert-warning">Kitap adı boş olamaz!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Kitap Ekle</title>
</head>
<body class="bg-light p-5">
    <div class="card p-4 mx-auto shadow" style="max-width: 600px; border-radius:15px;">
        <h3 class="mb-4 text-primary"><i class="fas fa-book"></i> Yeni Kitap Kaydı</h3>
        <?= $mesaj ?>
        <form method="POST">
            <div class="mb-3"><label class="form-label fw-bold">Kitap Adı</label><input type="text" name="kitap_adi" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-bold">Yazar</label><input type="text" name="yazar" class="form-control"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Yayın Evi</label><input type="text" name="yayin_evi" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Basım Yılı</label><input type="text" name="basim_yili" class="form-control" maxlength="4"></div>
            </div>
            <div class="mb-3"><label class="form-label fw-bold">Durum</label>
                <select name="durum" class="form-select">
                    <option value="Ofiste">Ofiste</option>
                    <option value="Ödünç Verildi">Ödünç Verildi</option>
                    <option value="Kayıp">Kayıp</option>
                </select>
            </div>
            <button class="btn btn-primary w-100 mt-3 fw-bold">KAYDET</button>
            <a href="yonetim_paneli.php?sayfa=kutuphane" class="btn btn-link w-100 mt-2 text-muted">İptal</a>
        </form>
    </div>
</body>
</html>
<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { header("Location: giris_final.php"); exit; }

$mesaj = "";

if ($_POST) {
    $dosya_no = $_POST['dosya_no'];
    $tarih    = $_POST['tarih'];
    $mahkeme  = $_POST['mahkeme']; 
    $kategori = $_POST['kategori']; 
    $davaci   = $_POST['davaci'];
    $davali   = $_POST['davali'];
    $durum    = $_POST['durum'];
    
   
    $ekle = $db->prepare("INSERT INTO davalar (ekleyen_id, dosya_no, tarih, mahkeme, kategori, davaci, davali, durum) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
 
    $sonuc = $ekle->execute([$_SESSION['id'], $dosya_no, $tarih, $mahkeme, $kategori, $davaci, $davali, $durum]);

    if ($sonuc) {
        $mesaj = '<div class="alert alert-success">Dosya başarıyla eklendi!</div>';
        header("refresh:2; url=yonetim_paneli.php?sayfa=dosya_takip");
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Dosya Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="card p-4 mx-auto shadow" style="max-width: 800px; border-radius:15px;">
        <h3 class="mb-4 text-primary">📂 Yeni Dava Dosyası Ekle</h3>
        <?= $mesaj ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Dosya No</label>
                    <input type="text" name="dosya_no" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Açılış Tarihi</label>
                    <input type="date" name="tarih" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">Mahkeme Seçin</label>
                    <select name="mahkeme" class="form-select" required>
                        <option value="">-- Mahkeme Seçiniz --</option>
                        <?php
                        $m_cek = $db->query("SELECT mahkeme_adi FROM tanim_mahkemeler ORDER BY mahkeme_adi ASC");
                        while($m = $m_cek->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$m['mahkeme_adi']}'>{$m['mahkeme_adi']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">Dosya Kategorisi</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Kategori Seçiniz --</option>
                        <?php
                        $k_cek = $db->query("SELECT kategori_adi FROM tanim_kategoriler ORDER BY kategori_adi ASC");
                        while($k = $k_cek->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$k['kategori_adi']}'>{$k['kategori_adi']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Davacı</label><input type="text" name="davaci" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Davalı</label><input type="text" name="davali" class="form-control"></div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Durum</label>
                    <select name="durum" class="form-select">
                        <option value="Açık">Açık</option>
                        <option value="Karar Aşaması">Karar Aşaması</option>
                        <option value="Kapalı">Kapalı</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary w-100 mt-3 py-2 fw-bold">DOSYAYI KAYDET</button>
            <a href="yonetim_paneli.php" class="btn btn-link w-100 mt-2 text-muted">Vazgeç</a>
        </form>
    </div>
</body>
</html>
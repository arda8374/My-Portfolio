<?php

session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum']) || !isset($_SESSION['id'])) { 
    header("Location: giris_final.php"); 
    exit; 
}

$mesaj = "";
$user_id = $_SESSION['id'];

if ($_POST) {
    $borclu   = $_POST['borclu'] ?? '';
    $alacakli = $_POST['alacakli'] ?? '';
    $tutar    = $_POST['tutar'] ?? '';
    $vade     = $_POST['vade'] ?? date('Y-m-d');
    $durum    = $_POST['durum'] ?? 'Bekliyor';
    $notlar   = $_POST['notlar'] ?? '';

    if (empty($borclu) || empty($tutar)) {
        $mesaj = '<div class="alert alert-warning">Borçlu adı ve Tutar zorunludur.</div>';
    } else {
        try {
           
            $ekle = $db->prepare("INSERT INTO senetler (ekleyen_id, borclu, alacakli, tutar, vade_tarihi, durum, notlar) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sonuc = $ekle->execute([$user_id, $borclu, $alacakli, $tutar, $vade, $durum, $notlar]);

            if ($sonuc) {
                $mesaj = '<div class="alert alert-success">✅ Senet sisteme işlendi!</div>';
                header("refresh:2; url=yonetim_paneli.php?sayfa=senet");
            } else {
                $mesaj = '<div class="alert alert-danger">Veritabanına ekleme yapılamadı.</div>';
            }
        } catch (PDOException $e) {
           
            $mesaj = '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senet Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #2c3e50; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; font-size: 16px; color: #b8c7ce; display: block; transition: 0.3s; }
        .sidebar a:hover { background-color: #1a252f; color: #fff; border-left: 4px solid #3498db; }
        .sidebar .brand { color: #fff; text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 30px; }
        .content { margin-left: 250px; padding: 30px; }
        .card-custom { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="fas fa-balance-scale"></i> HukukSis</div>
        <a href="yonetim_paneli.php?sayfa=senet"><i class="fas fa-arrow-left"></i> Senet Listesi</a>
        <a href="#" style="background:#1a252f; color:white;"><i class="fas fa-money-bill-wave"></i> Senet Ekle</a>
    </div>

    <div class="content">
        <div class="card-custom">
            <h3 class="mb-4 text-success"><i class="fas fa-money-check-alt"></i> Yeni Senet Girişi</h3>
            <?php echo $mesaj; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Borçlu Adı Soyadı</label>
                        <input type="text" name="borclu" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alacaklı (Müvekkil)</label>
                        <input type="text" name="alacakli" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-danger">Senet Tutarı (TL)</label>
                        <div class="input-group">
                            <span class="input-group-text">₺</span>
                            <input type="number" step="0.01" name="tutar" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Vade Tarihi</label>
                        <input type="date" name="vade" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Durum</label>
                        <select name="durum" class="form-select">
                            <option value="Bekliyor">Bekliyor</option>
                            <option value="İcrada">İcrada / Takipte</option>
                            <option value="Tahsil Edildi">Tahsil Edildi</option>
                            <option value="İptal">İptal / İade</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notlar</label>
                        <textarea name="notlar" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-save"></i> KAYDET</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
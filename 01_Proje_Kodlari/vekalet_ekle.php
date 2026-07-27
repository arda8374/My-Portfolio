<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}

$mesaj = "";

if ($_POST) {
    $muvekkil = $_POST['muvekkil'] ?? '';
    $noter    = $_POST['noter'] ?? '';
    $yevmiye  = $_POST['yevmiye'] ?? '';
    $tarih    = $_POST['tarih'] ?? date('Y-m-d');
    $notlar   = $_POST['notlar'] ?? '';
    $user_id  = $_SESSION['id']; 

    if (empty($muvekkil) || empty($noter)) {
        $mesaj = '<div class="alert alert-warning">Müvekkil Adı ve Noter zorunludur.</div>';
    } else {
        
        $kontrol = $db->prepare("SELECT id FROM vekaletnameler WHERE noter = ? AND yevmiye_no = ?");
        $kontrol->execute([$noter, $yevmiye]);

        if ($kontrol->rowCount() > 0) {
            $mesaj = '<div class="alert alert-danger">❌ HATA: Bu noter ve yevmiye numarasıyla daha önce bir kayıt yapılmış!</div>';
        } else {

            $ekle = $db->prepare("INSERT INTO vekaletnameler (ekleyen_id, muvekkil, noter, yevmiye_no, tarih, notlar) VALUES (?, ?, ?, ?, ?, ?)");
            $sonuc = $ekle->execute([$user_id, $muvekkil, $noter, $yevmiye, $tarih, $notlar]);

            if ($sonuc) {
                $mesaj = '<div class="alert alert-success">✅ Vekaletname başarıyla eklendi! Yönlendiriliyorsunuz...</div>';
                header("refresh:2; url=yonetim_paneli.php?sayfa=vekaletname");
            } else {
                $mesaj = '<div class="alert alert-danger">Veritabanı hatası oluştu.</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vekaletname Ekle</title>
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
        <a href="yonetim_paneli.php?sayfa=vekaletname"><i class="fas fa-arrow-left"></i> Vekalet Listesi</a>
        <a href="#" style="background:#1a252f; color:white;"><i class="fas fa-file-signature"></i> Vekalet Ekle</a>
    </div>

    <div class="content">
        <div class="card-custom">
            <h3 class="mb-4 text-primary"><i class="fas fa-file-contract"></i> Yeni Vekaletname Girişi</h3>
            
            <?php echo $mesaj; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vekalet Veren (Müvekkil)</label>
                        <input type="text" name="muvekkil" class="form-control" placeholder="Ad Soyad / Şirket" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Noter Adı</label>
                        <input type="text" name="noter" class="form-control" placeholder="Örn: Beyoğlu 3. Noterliği" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="tarih" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Yevmiye No</label>
                        <input type="text" name="yevmiye" class="form-control" placeholder="Örn: 12345">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notlar / Özel Yetkiler</label>
                        <textarea name="notlar" class="form-control" rows="3" placeholder="Ahzu kabz, sulh ibra yetkisi var mı?"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> KAYDET VE SİSTEME İŞLE</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}

$mesaj = "";

if ($_POST) {
   
    $ad_soyad = $_POST['ad_soyad'] ?? '';
    $telefon  = $_POST['telefon'] ?? '';
    $email    = $_POST['email'] ?? '';
    $adres    = $_POST['adres'] ?? '';
    $kategori = $_POST['kategori'] ?? 'Müvekkil';
    $user_id  = $_SESSION['id']; 

    
    if (empty($ad_soyad)) {
        $mesaj = '<div class="alert alert-warning">İsim Soyisim alanı boş bırakılamaz.</div>';
    } 
    else {
       
        $kontrol = $db->prepare("SELECT id FROM adres_defteri WHERE telefon = ? AND ekleyen_id = ?");
        $kontrol->execute([$telefon, $user_id]);

        if (!empty($telefon) && $kontrol->rowCount() > 0) {
            
            $mesaj = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Bu numara zaten SİZİN rehberinizde kayıtlı!</div>';
        } 
        else {
           
            $ekle = $db->prepare("INSERT INTO adres_defteri (ekleyen_id, ad_soyad, telefon, email, adres, kategori) VALUES (?, ?, ?, ?, ?, ?)");
            $sonuc = $ekle->execute([$user_id, $ad_soyad, $telefon, $email, $adres, $kategori]);

            if ($sonuc) {
                $mesaj = '<div class="alert alert-success">✅ Kişi başarıyla rehbere eklendi!</div>';
                header("refresh:2; url=yonetim_paneli.php?sayfa=adres");
            } else {
                $mesaj = '<div class="alert alert-danger">Kayıt sırasında bir hata oluştu.</div>';
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
    <title>Kişi Ekle</title>
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
        <a href="yonetim_paneli.php?sayfa=adres"><i class="fas fa-arrow-left"></i> Adres Defterine Dön</a>
        <a href="#" style="background:#1a252f; color:white;"><i class="fas fa-user-plus"></i> Kişi Ekle</a>
    </div>

    <div class="content">
        <div class="card-custom">
            <h3 class="mb-4 text-success"><i class="fas fa-address-book"></i> Rehbere Yeni Kişi Ekle</h3>
            
            <?php echo $mesaj; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ad Soyad / Firma Ünvanı</label>
                        <input type="text" name="ad_soyad" class="form-control" placeholder="Örn: Ahmet Yılmaz" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="Müvekkil">Müvekkil</option>
                            <option value="Avukat">Avukat</option>
                            <option value="Bilirkişi">Bilirkişi / Uzman</option>
                            <option value="Mahkeme Kalemi">Mahkeme Kalemi</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Telefon Numarası</label>
                        <input type="text" name="telefon" class="form-control" placeholder="05XX XXX XX XX">
                        <small class="text-muted">Aynı numara tekrar kaydedilemez.</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">E-Posta Adresi</label>
                        <input type="email" name="email" class="form-control" placeholder="mail@ornek.com">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Adres / Notlar</label>
                        <textarea name="adres" class="form-control" rows="3" placeholder="Açık adres veya kişiyle ilgili notlar..."></textarea>
                    </div>
                </div>
                
                <div class="mt-4 d-grid">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i> REHBERE KAYDET</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
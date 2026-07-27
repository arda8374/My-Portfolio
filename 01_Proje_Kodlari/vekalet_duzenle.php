<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}


if (!isset($_GET['id'])) { 
    header("Location: yonetim_paneli.php?sayfa=vekaletname"); 
    exit; 
}

$id = $_GET['id'];
$user_id = $_SESSION['id']; 
$rol = $_SESSION['rol'];  
$mesaj = "";


try {
    if ($rol == 'admin') {
        $sorgu = $db->prepare("SELECT * FROM vekaletnameler WHERE id = ?");
        $sorgu->execute([$id]);
    } else {
        $sorgu = $db->prepare("SELECT * FROM vekaletnameler WHERE id = ? AND ekleyen_id = ?");
        $sorgu->execute([$id, $user_id]);
    }
    $veri = $sorgu->fetch(PDO::FETCH_ASSOC);

   
    if (!$veri) { 
        header("Location: yonetim_paneli.php?sayfa=vekaletname"); 
        exit; 
    }
} catch (PDOException $e) { 
    die("Hata: " . $e->getMessage()); 
}


if ($_POST) {
    $muvekkil = $_POST['muvekkil'] ?? '';
    $noter    = $_POST['noter'] ?? '';
    $yevmiye  = $_POST['yevmiye'] ?? '';
    $tarih    = $_POST['tarih'] ?? date('Y-m-d');
    $notlar   = $_POST['notlar'] ?? '';

    if (!empty($muvekkil) && !empty($noter)) {
        if ($rol == 'admin') {
          
            $guncelle = $db->prepare("UPDATE vekaletnameler SET muvekkil=?, noter=?, yevmiye_no=?, tarih=?, notlar=? WHERE id=?");
            $sonuc = $guncelle->execute([$muvekkil, $noter, $yevmiye, $tarih, $notlar, $id]);
        } else {
          
            $guncelle = $db->prepare("UPDATE vekaletnameler SET muvekkil=?, noter=?, yevmiye_no=?, tarih=?, notlar=? WHERE id=? AND ekleyen_id=?");
            $sonuc = $guncelle->execute([$muvekkil, $noter, $yevmiye, $tarih, $notlar, $id, $user_id]);
        }

        if ($sonuc) {
            $mesaj = '<div class="alert alert-success">✅ Vekaletname güncellendi!</div>';
            header("refresh:2; url=yonetim_paneli.php?sayfa=vekaletname");
        } else {
            $mesaj = '<div class="alert alert-danger">Güncelleme başarısız.</div>';
        }
    } else {
        $mesaj = '<div class="alert alert-warning">Müvekkil ve Noter boş olamaz.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vekalet Düzenle</title>
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
        <div class="small text-uppercase text-muted ms-3 mb-2 ps-3" style="font-size:10px;">MENÜ</div>
        <a href="yonetim_paneli.php?sayfa=vekaletname"><i class="fas fa-arrow-left me-2"></i> Listeye Dön</a>
        <a href="#" class="active" style="background:#1a252f; color:white;"><i class="fas fa-edit me-2"></i> Düzenleme Modu</a>
    </div>

    <div class="content">
        <div class="card-custom">
            <h3 class="mb-4 text-warning">
                <i class="fas fa-edit"></i> Vekaletnameyi Düzenle 
                <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6 ms-2">Admin Modu</span>' : '') ?>
            </h3>
            <?php echo $mesaj; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vekalet Veren</label>
                        <input type="text" name="muvekkil" class="form-control" value="<?php echo htmlspecialchars($veri['muvekkil']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Noter Adı</label>
                        <input type="text" name="noter" class="form-control" value="<?php echo htmlspecialchars($veri['noter']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="tarih" class="form-control" value="<?php echo $veri['tarih']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Yevmiye No</label>
                        <input type="text" name="yevmiye" class="form-control" value="<?php echo htmlspecialchars($veri['yevmiye_no']); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notlar</label>
                        <textarea name="notlar" class="form-control" rows="3"><?php echo htmlspecialchars($veri['notlar']); ?></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning w-100 fw-bold"><i class="fas fa-save"></i> DEĞİŞİKLİKLERİ KAYDET</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
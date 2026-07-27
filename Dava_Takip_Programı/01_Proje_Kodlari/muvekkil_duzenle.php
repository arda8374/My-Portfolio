<?php

session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: yonetim_paneli.php?sayfa=adres");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['id']; 
$rol = $_SESSION['rol'];  


if ($rol == 'admin') {
    $sorgu = $db->prepare("SELECT * FROM muvekkiller WHERE id = :id");
    $sorgu->execute(['id' => $id]);
} else {
    $sorgu = $db->prepare("SELECT * FROM muvekkiller WHERE id = :id AND ekleyen_id = :user_id");
    $sorgu->execute(['id' => $id, 'user_id' => $user_id]);
}
$muvekkil = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$muvekkil) {
    header("Location: yonetim_paneli.php?sayfa=adres");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];

    if ($rol == 'admin') {
     
        $sql = "UPDATE muvekkiller SET ad_soyad = :ad_soyad, telefon = :telefon, email = :email WHERE id = :id";
        $stmt = $db->prepare($sql);
        $sonuc = $stmt->execute(['ad_soyad' => $ad_soyad, 'telefon' => $telefon, 'email' => $email, 'id' => $id]);
    } else {
    
        $sql = "UPDATE muvekkiller SET ad_soyad = :ad_soyad, telefon = :telefon, email = :email WHERE id = :id AND ekleyen_id = :user_id";
        $stmt = $db->prepare($sql);
        $sonuc = $stmt->execute(['ad_soyad' => $ad_soyad, 'telefon' => $telefon, 'email' => $email, 'id' => $id, 'user_id' => $user_id]);
    }

    if ($sonuc) {
        header("Location: yonetim_paneli.php?sayfa=adres");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müvekkil Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; max-width: 450px; margin: 50px auto; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<div class="form-container">
    <h2 class="text-center mb-4" style="color:#0a2342;">Bilgileri Düzenle <?= ($rol == 'admin' ? '<span class="badge bg-danger fs-6">Admin</span>' : '') ?></h2>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Adı Soyadı:</label>
            <input type="text" name="ad_soyad" class="form-control" value="<?= htmlspecialchars($muvekkil['ad_soyad']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Telefon Numarası:</label>
            <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($muvekkil['telefon']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">E-posta Adresi:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($muvekkil['email']); ?>">
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Güncelle</button>
        <a href="yonetim_paneli.php?sayfa=adres" class="btn btn-secondary w-100 mt-2 py-2 text-decoration-none">İptal</a>
    </form>
</div>

</body>
</html>
<?php
session_start();
require 'baglanti.php';

if (!isset($_SESSION['giris_yapti']) || $_SESSION['giris_yapti'] !== true) {
    header("Location: giris-sayfasi1.html");
    exit;
}

$muvekkiller = $db->query("SELECT * FROM muvekkiller ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müvekkil Listesi</title>
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
        body { background: #eee; font-family: Arial, sans-serif; }
        .liste-kapsayici { width: 80%; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
        th { background-color: #0a2342; color: white; }
        .geri-btn { background: #6c757d; text-decoration: none; color: white; padding: 5px 10px; border-radius: 4px; }
        
       
        .sil-btn { background: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .duzenle-btn { background: #007bff; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin-right: 5px; }
        
        .btn-ekle { background:#28a745; float:right; text-decoration: none; color: white; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="liste-kapsayici">
    <a href="yonetim_paneli.php" class="geri-btn">← Panele Dön</a>
    <a href="muvekkil_ekle.php" class="btn-ekle">+ Yeni Ekle</a>
    
    <h2 style="margin-top:15px;">Kayıtlı Müvekkiller</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($muvekkiller as $m): ?>
            <tr>
                <td><?php echo $m['id']; ?></td>
                <td><b><?php echo $m['ad_soyad']; ?></b></td>
                <td><?php echo $m['telefon']; ?></td>
                <td><?php echo $m['email']; ?></td>
                <td>
                    <a href="muvekkil_duzenle.php?id=<?php echo $m['id']; ?>" class="duzenle-btn">Düzenle</a>
                    <a href="sil.php?tur=muvekkil&id=<?php echo $m['id']; ?>" class="sil-btn" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if(count($muvekkiller) == 0): ?>
        <p style="text-align:center; color:#777; margin-top:20px;">Henüz kayıtlı müvekkil yok.</p>
    <?php endif; ?>
</div>

</body>
</html>
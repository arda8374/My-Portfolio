<?php
ob_start();
session_start();
require 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_test.php"); 
    exit; 
}

$sql = "SELECT * FROM davalar ORDER BY id DESC";

try {
    $davalar = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı Hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dava Listesi</title>
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
      
        body { background: #eee; font-family: 'Segoe UI', sans-serif; }
        .kapsayici { width: 95%; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        th { background-color: #0a2342; color: white; white-space: nowrap; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        
        .durum-aktif { color: green; font-weight: bold; }
        .durum-kapali { color: red; }
        
        .btn-ekle { background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; float: right; }
        .btn-sil { background: #dc3545; color: white; padding: 3px 8px; text-decoration: none; border-radius: 3px; font-size: 11px; }
    </style>
</head>
<body>

<div class="kapsayici">
    <a href="yonetim_paneli.php" style="text-decoration:none; color:#555;">← Panele Dön</a>
    <a href="dava_ekle.php" class="btn-ekle">+ Yeni Dosya Aç</a>
    
    <h2 style="margin-top:10px;">Dava Dosyaları</h2>
    
    <table>
        <thead>
            <tr>
                <th>Dosya No</th>
                <th>Esas No</th>
                <th>Büro No</th>
                <th>Müvekkil</th>
                <th>Karşı Taraf</th>
                <th>Mahkeme</th>
                <th>Konu</th>
                <th>Dur. Tarihi</th>
                <th>Saat</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($davalar as $d): ?>
            <tr>
                <td><b><?php echo isset($d['dosya_no']) ? $d['dosya_no'] : '-'; ?></b></td>
                <td><?php echo isset($d['esas_no']) ? $d['esas_no'] : '-'; ?></td>
                <td><?php echo isset($d['buro_no']) ? $d['buro_no'] : '-'; ?></td>
                
                <td>
                    <?php 
                        if(isset($d['muvekkil_ad'])) echo $d['muvekkil_ad'];
                        elseif(isset($d['muvekkil'])) echo $d['muvekkil'];
                        elseif(isset($d['ad_soyad'])) echo $d['ad_soyad'];
                        else echo "-";
                    ?>
                </td>

                <td><?php echo isset($d['karsi_taraf']) ? $d['karsi_taraf'] : '-'; ?></td>
                <td><?php echo isset($d['mahkeme']) ? $d['mahkeme'] : '-'; ?></td>
                <td><?php echo isset($d['dava_konusu']) ? substr($d['dava_konusu'], 0, 30) . '...' : '-'; ?></td>
                <td><?php echo isset($d['durusma_tarihi']) ? $d['durusma_tarihi'] : '-'; ?></td>
                <td><?php echo isset($d['durusma_saati']) ? substr($d['durusma_saati'], 0, 5) : '-'; ?></td>
                <td>
                    <?php 
                        $durum = isset($d['durum']) ? $d['durum'] : '';
                        if($durum == 'Aktif'): 
                    ?>
                        <span class="durum-aktif">Aktif</span>
                    <?php else: ?>
                        <span class="durum-kapali"><?php echo $durum; ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="sil.php?tur=dava&id=<?php echo $d['id']; ?>" class="btn-sil" onclick="return confirm('Silmek istiyor musunuz?')">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if(count($davalar) == 0): ?>
        <p style="text-align:center; padding:20px; color:#777;">Henüz kayıtlı bir dava dosyası yok.</p>
    <?php endif; ?>
</div>

</body>
</html>
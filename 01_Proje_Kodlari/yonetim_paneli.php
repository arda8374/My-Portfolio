<?php

date_default_timezone_set('Europe/Istanbul');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'baglanti.php';


if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}

$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 'raporlar';
$user_name = isset($_SESSION['kullanici_adi']) ? $_SESSION['kullanici_adi'] : "Avukat Bey";
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; 
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'user';


$t_dava = 0; $a_dava = 0; $t_para = 0; $t_senet = 0; $t_muv = 0;
if (isset($db)) {
    try {
        if ($rol == 'admin') {
            $t_dava = $db->query("SELECT COUNT(*) FROM davalar")->fetchColumn() ?: 0;
            $a_dava = $db->query("SELECT COUNT(*) FROM davalar WHERE durum='Açık'")->fetchColumn() ?: 0;
            $t_muv = $db->query("SELECT COUNT(*) FROM adres_defteri")->fetchColumn() ?: 0;
            $t_para = $db->query("SELECT SUM(net_alinan) FROM smm_makbuzlar")->fetchColumn() ?: 0;
            $t_senet = $db->query("SELECT SUM(tutar) FROM senetler WHERE durum='Bekliyor'")->fetchColumn() ?: 0;
        } else {
            $t_d_s = $db->prepare("SELECT COUNT(*) FROM davalar WHERE ekleyen_id = ?");
            $t_d_s->execute([$user_id]); $t_dava = $t_d_s->fetchColumn() ?: 0;

            $a_d_s = $db->prepare("SELECT COUNT(*) FROM davalar WHERE ekleyen_id = ? AND durum='Açık'");
            $a_d_s->execute([$user_id]); $a_dava = $a_d_s->fetchColumn() ?: 0;

            $t_m_s = $db->prepare("SELECT COUNT(*) FROM adres_defteri WHERE ekleyen_id = ?");
            $t_m_s->execute([$user_id]); $t_muv = $t_m_s->fetchColumn() ?: 0;

            $t_p_s = $db->prepare("SELECT SUM(net_alinan) FROM smm_makbuzlar WHERE ekleyen_id = ?");
            $t_p_s->execute([$user_id]); $t_para = $t_p_s->fetchColumn() ?: 0;

            $t_s_s = $db->prepare("SELECT SUM(tutar) FROM senetler WHERE ekleyen_id = ? AND durum='Bekliyor'");
            $t_s_s->execute([$user_id]); $t_senet = $t_s_s->fetchColumn() ?: 0;
        }
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HukukSis v2.0 Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; }
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #2c3e50; padding-top: 20px; color: white; z-index: 1000; }
        .sidebar a { padding: 12px 25px; text-decoration: none; font-size: 14px; color: #b8c7ce; display: block; border-left: 3px solid transparent; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1a252f; color: #fff; border-left: 3px solid #3498db; }
        .sidebar .brand { font-size: 22px; text-align: center; font-weight: bold; margin-bottom: 25px; border-bottom: 1px solid #3e4f5f; padding-bottom: 15px; color: #fff; }
        .content { margin-left: 250px; padding: 25px; min-height: 100vh; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; margin-bottom: 25px; }
        .stat-card { border-left: 5px solid; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="fas fa-balance-scale"></i> HukukSis</div>
        <div class="small text-uppercase text-muted ms-3 mb-2" style="font-size:10px;">ANA MENÜ</div>
        <a href="yonetim_paneli.php?sayfa=raporlar" class="<?= ($sayfa=='raporlar')?'active':'' ?>"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
        <a href="yonetim_paneli.php?sayfa=dosya_takip" class="<?= ($sayfa=='dosya_takip')?'active':'' ?>"><i class="fas fa-folder-open me-2"></i> Dosya Takip</a>
        <a href="yonetim_paneli.php?sayfa=vekaletname" class="<?= ($sayfa=='vekaletname')?'active':'' ?>"><i class="fas fa-file-contract me-2"></i> Vekalet Arşivi</a>
        <a href="yonetim_paneli.php?sayfa=adres" class="<?= ($sayfa=='adres')?'active':'' ?>"><i class="fas fa-address-book me-2"></i> Adres Defteri</a>
        <a href="yonetim_paneli.php?sayfa=senet" class="<?= ($sayfa=='senet')?'active':'' ?>"><i class="fas fa-money-check-alt me-2"></i> Senet Takip</a>
        <a href="yonetim_paneli.php?sayfa=smm" class="<?= ($sayfa=='smm')?'active':'' ?>"><i class="fas fa-calculator me-2"></i> SMM Takip</a>
        <a href="yonetim_paneli.php?sayfa=kutuphane" class="<?= ($sayfa=='kutuphane')?'active':'' ?>"><i class="fas fa-book me-2"></i> Kütüphane</a>
        
        <div class="mt-4 border-top pt-3">
            <div class="small text-uppercase text-muted ms-3 mb-2" style="font-size:10px;">SİSTEM</div>
            <a href="yonetim_paneli.php?sayfa=tanimlar" class="<?= ($sayfa=='tanimlar')?'active':'' ?>"><i class="fas fa-cogs me-2"></i> Tanımlar</a>
            <a href="profil.php" class="text-info"><i class="fas fa-user-circle me-2"></i> Profilim</a>
            <a href="cikis.php" class="text-danger"><i class="fas fa-power-off me-2"></i> Çıkış Yap</a>
        </div>
    </div>

    <div class="content">
        <nav class="navbar navbar-light bg-white card-custom p-3 d-flex justify-content-between shadow-sm">
            <span class="h5 mb-0 text-secondary">Hoşgeldin, <strong><?= $user_name ?></strong></span>
            <div>
                <span id="canliSaat" class="badge bg-light text-dark border me-2 p-2 fw-bold" style="font-size: 1.1em;"><?= date("H:i:s") ?></span>
                <span class="fw-bold text-muted"><?= date("d.m.Y") ?></span>
            </div>
        </nav>

        <?php if ($sayfa == 'raporlar'): ?>
            <div class="row g-4 mb-4">
                <div class="col-md-3"><div class="card card-custom p-3 stat-card border-primary"><small class="text-muted fw-bold">TOPLAM DOSYA</small><h2 class="fw-bold"><?= $t_dava ?></h2><small><?= $a_dava ?> Aktif</small></div></div>
                <div class="col-md-3"><div class="card card-custom p-3 stat-card border-success"><small class="text-muted fw-bold">NET KAZANÇ</small><h2 class="text-success fw-bold"><?= number_format($t_para,0,',','.') ?> ₺</h2><small>SMM Geliri</small></div></div>
                <div class="col-md-3"><div class="card card-custom p-3 stat-card border-danger"><small class="text-muted fw-bold">BEKLEYEN ALACAK</small><h2 class="text-danger fw-bold"><?= number_format($t_senet,0,',','.') ?> ₺</h2><small>Senetler</small></div></div>
                <div class="col-md-3"><div class="card card-custom p-3 stat-card border-warning"><small class="text-muted fw-bold">MÜVEKKİLLER</small><h2 class="text-warning fw-bold"><?= $t_muv ?></h2><small>Rehber Kaydı</small></div></div>
            </div>

        <?php elseif ($sayfa == 'dosya_takip'): ?>
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-4"><h4>📂 Dosya Listesi</h4><a href="dava_ekle.php" class="btn btn-sm btn-primary">+ Yeni Dosya</a></div>
                <table class="table table-hover align-middle">
                    <thead class="table-dark"><tr><th>Dosya No</th><th>Mahkeme</th><th>Kategori</th><th>Durum</th><th class="text-center">İşlem</th></tr></thead>
                    <tbody>
                    <?php 
                        $sql = ($rol=='admin') ? "SELECT * FROM davalar WHERE 1=1" : "SELECT * FROM davalar WHERE ekleyen_id = ?";
                        $params = ($rol=='admin') ? [] : [$user_id];
                        $sql .= " ORDER BY id DESC";
                        $sorgu = $db->prepare($sql); $sorgu->execute($params);
                        while($v = $sorgu->fetch(PDO::FETCH_ASSOC)){ 
                            $bg = ($v['durum']=='Açık')?'bg-success':'bg-danger';
                            echo "<tr><td>{$v['dosya_no']}</td><td>{$v['mahkeme']}</td><td>{$v['kategori']}</td><td><span class='badge {$bg}'>{$v['durum']}</span></td><td class='text-center'><a href='duzenle.php?id={$v['id']}' class='text-primary me-2'><i class='fas fa-edit'></i></a><a href='sil.php?id={$v['id']}' class='text-danger' onclick='return confirm(\"Silinsin mi?\")'><i class='fas fa-trash'></i></a></td></tr>"; 
                        }
                    ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($sayfa == 'vekaletname'): ?>
            <div class="card card-custom p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-3"><h4>📜 Vekaletnameler</h4><a href="vekalet_ekle.php" class="btn btn-sm btn-warning">Yeni Kayıt</a></div>
                <table class="table table-hover"><thead><tr><th>Müvekkil</th><th>Noter</th><th>Tarih</th><th>İşlem</th></tr></thead><tbody>
                <?php 
                    $sql = ($rol=='admin') ? "SELECT * FROM vekaletnameler ORDER BY id DESC" : "SELECT * FROM vekaletnameler WHERE ekleyen_id = ? ORDER BY id DESC";
                    $sorgu = $db->prepare($sql); ($rol=='admin') ? $sorgu->execute() : $sorgu->execute([$user_id]);
                    while($v=$sorgu->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr><td><strong>{$v['muvekkil']}</strong></td><td>{$v['noter']}</td><td>".date('d.m.Y',strtotime($v['tarih']))."</td><td><a href='vekalet_duzenle.php?id={$v['id']}' class='me-2'><i class='fas fa-edit'></i></a><a href='vekalet_sil.php?id={$v['id']}' class='text-danger'><i class='fas fa-trash'></i></a></td></tr>";
                    } 
                ?>
                </tbody></table>
            </div>

        <?php elseif ($sayfa == 'adres'): ?>
            <div class="card card-custom p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-4"><h4>👤 Rehber</h4><a href="adres_ekle.php" class="btn btn-sm btn-success">+ Yeni Kişi</a></div>
                <div class="row">
                    <?php 
                    $sql = ($rol=='admin') ? "SELECT * FROM adres_defteri ORDER BY ad_soyad ASC" : "SELECT * FROM adres_defteri WHERE ekleyen_id = ? ORDER BY ad_soyad ASC";
                    $sorgu = $db->prepare($sql); ($rol=='admin') ? $sorgu->execute() : $sorgu->execute([$user_id]);
                    while($k = $sorgu->fetch(PDO::FETCH_ASSOC)) {
                        $r = 'bg-primary';
                        echo "<div class='col-md-4 mb-3'><div class='card h-100 border-0 shadow-sm'><div class='card-body'><div class='d-flex align-items-center mb-3'><div class='rounded-circle text-white d-flex align-items-center justify-content-center me-3 {$r}' style='width:40px; height:40px;'>".strtoupper(substr($k['ad_soyad'],0,1))."</div><div><h6 class='mb-0 fw-bold'>{$k['ad_soyad']}</h6><small class='text-muted'>{$k['kategori']}</small></div></div><p class='small text-muted'><i class='fas fa-phone me-1'></i> {$k['telefon']}</p><div class='d-flex gap-2'><a href='adres_duzenle.php?id={$k['id']}' class='btn btn-sm btn-outline-primary flex-grow-1'>Düzenle</a><a href='adres_sil.php?id={$k['id']}' class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></a></div></div></div></div>";
                    } ?>
                </div>
            </div>

        <?php elseif ($sayfa == 'senet'): ?>
            <div class="card card-custom p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-4"><h4>💸 Senetler</h4><a href="senet_ekle.php" class="btn btn-sm btn-danger">Yeni Senet</a></div>
                <div class="accordion" id="senetAcc">
                    <?php 
                    $sql = ($rol=='admin') ? "SELECT borclu, SUM(tutar) as t FROM senetler GROUP BY borclu" : "SELECT borclu, SUM(tutar) as t FROM senetler WHERE ekleyen_id = ? GROUP BY borclu";
                    $s_gr = $db->prepare($sql); ($rol=='admin') ? $s_gr->execute() : $s_gr->execute([$user_id]);
                    $idx=0; while($g = $s_gr->fetch(PDO::FETCH_ASSOC)){ $idx++; $cid="c".$idx; ?>
                    <div class="accordion-item shadow-sm mb-2"><h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#<?=$cid?>"><strong><?=$g['borclu']?></strong> <span class="ms-auto me-3 text-danger"><?=number_format($g['t'],2,',','.')?> ₺</span></button></h2>
                    <div id="<?=$cid?>" class="accordion-collapse collapse" data-bs-parent="#senetAcc"><div class="accordion-body px-0"><table class="table table-sm mb-0"><thead><tr><th class="ps-4">Vade</th><th>Tutar</th><th class="text-end pe-4">İşlem</th></tr></thead><tbody>
                    <?php 
                        $sql_d = ($rol=='admin') ? "SELECT * FROM senetler WHERE borclu=?" : "SELECT * FROM senetler WHERE borclu=? AND ekleyen_id=?";
                        $params_d = ($rol=='admin') ? [$g['borclu']] : [$g['borclu'], $user_id];
                        $d = $db->prepare($sql_d); $d->execute($params_d); 
                        while($s=$d->fetch(PDO::FETCH_ASSOC)){ 
                            echo "<tr><td class='ps-4'>".date('d.m.Y',strtotime($s['vade_tarihi']))."</td><td>".number_format($s['tutar'],2,',','.')." ₺</td><td class='text-end pe-4'><a href='senet_duzenle.php?id={$s['id']}' class='text-primary me-2'><i class='fas fa-edit'></i></a><a href='senet_sil.php?id={$s['id']}' class='text-danger'><i class='fas fa-trash'></i></a></td></tr>";
                        } 
                    ?>
                    </tbody></table></div></div></div>
                    <?php } ?>
                </div>
            </div>

        <?php elseif ($sayfa == 'smm'): ?>
            <div class="card card-custom p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-4"><h4>📊 SMM Makbuzları</h4><a href="smm_ekle.php" class="btn btn-sm btn-success">+ Makbuz Kes</a></div>
                <table class="table table-hover align-middle"><thead><tr><th>Tarih</th><th>Müvekkil</th><th>Net Tutar</th><th class="text-center">İşlem</th></tr></thead><tbody>
                <?php 
                    $sql = ($rol=='admin') ? "SELECT * FROM smm_makbuzlar ORDER BY tarih DESC" : "SELECT * FROM smm_makbuzlar WHERE ekleyen_id = ? ORDER BY tarih DESC";
                    $sorgu = $db->prepare($sql); ($rol=='admin') ? $sorgu->execute() : $sorgu->execute([$user_id]);
                    while($m = $sorgu->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr><td>".date('d.m.Y',strtotime($m['tarih']))."</td><td class='fw-bold'>{$m['muvekkil_adi']}</td><td class='fw-bold text-success'>".number_format($m['net_alinan'],2,',','.')." ₺</td><td class='text-center'><a href='smm_duzenle.php?id={$m['id']}' class='text-primary me-2'><i class='fas fa-edit'></i></a><a href='smm_sil.php?id={$m['id']}' class='text-danger'><i class='fas fa-trash'></i></a></td></tr>";
                    } 
                ?>
                </tbody></table>
            </div>

        <?php elseif ($sayfa == 'kutuphane'): ?>
            <div class="card card-custom p-4 shadow-sm">
                <div class="d-flex justify-content-between mb-4"><h4>📚 Kütüphane</h4><a href="kitap_ekle.php" class="btn btn-sm btn-info text-white">Kitap Ekle</a></div>
                <table class="table align-middle"><thead><tr><th>Kitap Adı</th><th>Durum</th><th class="text-center">İşlem</th></tr></thead><tbody>
                <?php 
                    $sql = ($rol=='admin') ? "SELECT * FROM kutuphane ORDER BY kitap_adi ASC" : "SELECT * FROM kutuphane WHERE ekleyen_id = ? ORDER BY kitap_adi ASC";
                    $sorgu = $db->prepare($sql); ($rol=='admin') ? $sorgu->execute() : $sorgu->execute([$user_id]);
                    while($v = $sorgu->fetch(PDO::FETCH_ASSOC)){
                        $cl = ($v['durum']=='Ofiste')?'bg-success':'bg-warning text-dark';
                        echo "<tr><td><div class='fw-bold'>{$v['kitap_adi']}</div><small>{$v['yazar']}</small></td><td><span class='badge {$cl}'>{$v['durum']}</span></td><td class='text-center'><a href='kitap_duzenle.php?id={$v['id']}' class='me-2'><i class='fas fa-edit'></i></a><a href='kitap_sil.php?id={$v['id']}' class='text-danger'><i class='fas fa-trash'></i></a></td></tr>";
                    } 
                ?>
                </tbody></table>
            </div>

        <?php elseif ($sayfa == 'tanimlar'): ?>
            <div class="row">
                <div class="col-md-6 mb-4"><div class="card card-custom p-4 h-100 shadow-sm"><h4>🏛️ Mahkemeler</h4><form action="tanim_islem.php?islem=mahkeme_ekle" method="POST" class="d-flex mb-3"><input type="text" name="mahkeme_adi" class="form-control me-2" required><button class="btn btn-primary">Ekle</button></form><ul class="list-group list-group-flush overflow-auto" style="max-height: 250px;">
                <?php $m = $db->query("SELECT * FROM tanim_mahkemeler ORDER BY mahkeme_adi ASC"); while($r = $m->fetch(PDO::FETCH_ASSOC)){ echo "<li class='list-group-item d-flex justify-content-between'>{$r['mahkeme_adi']} <a href='tanim_islem.php?islem=mahkeme_sil&id={$r['id']}' class='text-danger'><i class='fas fa-times'></i></a></li>"; } ?>
                </ul></div></div>
                <div class="col-md-6 mb-4"><div class="card card-custom p-4 h-100 shadow-sm"><h4>📂 Kategoriler</h4><form action="tanim_islem.php?islem=kategori_ekle" method="POST" class="d-flex mb-3"><input type="text" name="kategori_adi" class="form-control me-2" required><button class="btn btn-success">Ekle</button></form><ul class="list-group list-group-flush overflow-auto" style="max-height: 250px;">
                <?php $k = $db->query("SELECT * FROM tanim_kategoriler ORDER BY kategori_adi ASC"); while($r = $k->fetch(PDO::FETCH_ASSOC)){ echo "<li class='list-group-item d-flex justify-content-between'>{$r['kategori_adi']} <a href='tanim_islem.php?islem=kategori_sil&id={$r['id']}' class='text-danger'><i class='fas fa-times'></i></a></li>"; } ?>
                </ul></div></div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saatiGuncelle() {
            var simdi = new Date();
            document.getElementById('canliSaat').innerText = simdi.getHours().toString().padStart(2, '0') + ":" + simdi.getMinutes().toString().padStart(2, '0') + ":" + simdi.getSeconds().toString().padStart(2, '0');
        }
        setInterval(saatiGuncelle, 1000); 
    </script>
</body>
</html>
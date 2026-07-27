<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) { 
    header("Location: giris_final.php"); 
    exit; 
}

$mesaj = "";
if ($_POST) {
    $muvekkil     = $_POST['muvekkil_adi'];
    $brut         = $_POST['brut_tutar'];
    $stopaj_orani = $_POST['stopaj_orani'];
    $kdv_orani    = $_POST['kdv_orani'];
    $tarih        = $_POST['tarih'];
    $aciklama     = $_POST['aciklama'];
    $user_id      = $_SESSION['id']; 

   
    $stopaj_tutar = $brut * ($stopaj_orani / 100);
    $kdv_tutar    = $brut * ($kdv_orani / 100);
    $net_alinan   = ($brut - $stopaj_tutar) + $kdv_tutar;

    $ekle = $db->prepare("INSERT INTO smm_makbuzlar (ekleyen_id, muvekkil_adi, brut_tutar, stopaj_orani, kdv_orani, net_alinan, tarih, aciklama) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($ekle->execute([$user_id, $muvekkil, $brut, $stopaj_orani, $kdv_orani, $net_alinan, $tarih, $aciklama])) {
        $mesaj = '<div class="alert alert-success">✅ Makbuz başarıyla kesildi!</div>';
        header("refresh:2; url=yonetim_paneli.php?sayfa=smm");
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>SMM Makbuz Kes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .card { border-radius: 15px; border:none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .resul-box { background: #e9ecef; padding: 15px; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body class="p-5">
    <div class="container">
        <div class="card p-4 mx-auto" style="max-width: 700px;">
            <h3 class="text-success mb-4"><i class="fas fa-file-invoice-dollar"></i> Yeni SMM Makbuzu</h3>
            <?= $mesaj ?>
            <form method="POST" id="smmForm">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Müvekkil Adı</label>
                        <input type="text" name="muvekkil_adi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">Brüt Tutar (₺)</label>
                        <input type="number" step="0.01" name="brut_tutar" id="brut" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stopaj (%)</label>
                        <input type="number" name="stopaj_orani" id="stopaj_oran" class="form-control" value="20">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">KDV (%)</label>
                        <input type="number" name="kdv_orani" id="kdv_oran" class="form-control" value="20">
                    </div>
                    <div class="col-md-12">
                        <div class="resul-box text-center">
                            NET TAHSİLAT: <span id="net_sonuc" class="text-success">0.00</span> ₺
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="tarih" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 mt-4 py-2 fw-bold">MAKBUZU SİSTEME İŞLE</button>
                <a href="yonetim_paneli.php?sayfa=smm" class="btn btn-link w-100 mt-2 text-muted">Vazgeç</a>
            </form>
        </div>
    </div>

    <script>
        // Anlık Hesaplama Motoru
        const brutInput = document.getElementById('brut');
        const stopajInput = document.getElementById('stopaj_oran');
        const kdvInput = document.getElementById('kdv_oran');
        const netSonuc = document.getElementById('net_sonuc');

        function hesapla() {
            let brut = parseFloat(brutInput.value) || 0;
            let sOran = parseFloat(stopajInput.value) || 0;
            let kOran = parseFloat(kdvInput.value) || 0;

            let stopajTutar = brut * (sOran / 100);
            let kdvTutar = brut * (kOran / 100);
            let net = (brut - stopajTutar) + kdvTutar;

            netSonuc.innerText = net.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        [brutInput, stopajInput, kdvInput].forEach(el => el.addEventListener('input', hesapla));
    </script>
</body>
</html>
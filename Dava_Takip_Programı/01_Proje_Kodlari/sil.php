<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['oturum'])) {
    header("Location: giris_final.php");
    exit;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['id']; 
    $rol = $_SESSION['rol'];    

    
    if ($rol == 'admin') {
       
        $sil = $db->prepare("DELETE FROM davalar WHERE id = ?");
        $sil->execute([$id]);
    } else {
        
        $sil = $db->prepare("DELETE FROM davalar WHERE id = ? AND ekleyen_id = ?");
        $sil->execute([$id, $user_id]);
    }
}


header("Location: yonetim_paneli.php?sayfa=dosya_takip");
exit;
?>
<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['user'])) { header("Location: giris-sayfasi1.php"); exit; }

$islem = $_GET['islem'];


if ($islem == 'mahkeme_ekle' && $_POST) {
    $ad = $_POST['mahkeme_adi'];
    $db->prepare("INSERT INTO tanim_mahkemeler (mahkeme_adi) VALUES (?)")->execute([$ad]);
    header("Location: yonetim_paneli.php?sayfa=tanimlar");
}


elseif ($islem == 'mahkeme_sil') {
    $db->prepare("DELETE FROM tanim_mahkemeler WHERE id = ?")->execute([$_GET['id']]);
    header("Location: yonetim_paneli.php?sayfa=tanimlar");
}


elseif ($islem == 'kategori_ekle' && $_POST) {
    $ad = $_POST['kategori_adi'];
    $db->prepare("INSERT INTO tanim_kategoriler (kategori_adi) VALUES (?)")->execute([$ad]);
    header("Location: yonetim_paneli.php?sayfa=tanimlar");
}


elseif ($islem == 'kategori_sil') {
    $db->prepare("DELETE FROM tanim_kategoriler WHERE id = ?")->execute([$_GET['id']]);
    header("Location: yonetim_paneli.php?sayfa=tanimlar");
}
?>
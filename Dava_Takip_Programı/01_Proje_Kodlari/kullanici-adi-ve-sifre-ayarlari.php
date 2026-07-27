<?php

session_start();


$dogru_kullanici = "admin";
$dogru_sifre = "12345";


$gelen_kullanici = $_POST['kullanici'];
$gelen_sifre = $_POST['sifre'];


if ($gelen_kullanici == $dogru_kullanici && $gelen_sifre == $dogru_sifre) {
    
   
    $_SESSION['giris_yapti'] = true;
    $_SESSION['kullanici_adi'] = $gelen_kullanici; 
    
   
    header("Location: yonetim_paneli.php");
    exit; 
} else {
    
    echo "Kullanıcı adı veya şifre hatalı!";
    echo "<br>";
    echo "<a href='giris-sayfasi1.html'>Geri Dön</a>";
}

?>
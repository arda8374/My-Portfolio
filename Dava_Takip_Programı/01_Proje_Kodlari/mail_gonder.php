<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


if (isset($_POST['btnGonder'])) {
    
    $adsoyad = $_POST['adsoyad'];
    $email   = $_POST['email']; 
    $mesaj   = $_POST['mesaj'];

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;

    try {
    
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
      
        $mail->Username   = 'ozkula838@gmail.com'; 
        
       
        $mail->Password   = 'dhyl izxi uziq hvpn';   
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

       
        $mail->setFrom('ozkula838@gmail.com', 'Hukuk Bürosu İletişim');
        
   
        $mail->addAddress('ozkula838@gmail.com'); 

        
        $mail->addReplyTo($email, $adsoyad);

       
        $mail->isHTML(true);
        $mail->Subject = 'Siteden Yeni Mesaj: ' . $adsoyad;
        $mail->Body    = "<h3>Yeni İletişim Mesajı</h3>
                          <b>Gönderen:</b> $adsoyad <br>
                          <b>E-Posta:</b> $email <br>
                          <b>Mesaj:</b> <br>$mesaj";

        $mail->send();
        
       
        echo "<script>
                alert('Mesajınız başarıyla gönderildi! En kısa sürede dönüş yapılacaktır.'); 
                window.location.href='giris-sayfasi1.php';
              </script>";

    } catch (Exception $e) {
        echo "Mesaj gönderilemedi. Hata: {$mail->ErrorInfo}";
    }
}
?>
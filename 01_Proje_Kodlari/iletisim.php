<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İletişim - Hukuk Bürosu</title>
    <link rel="stylesheet" href="sayfa-ayarlari.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-top: 10px; color: #555; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { margin-top: 20px; width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; }
        .back-link:hover { color: #000; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Bize Ulaşın</h2>
    <form action="mail_gonder.php" method="post">
        <label>Adınız Soyadınız:</label>
        <input type="text" name="adsoyad" required placeholder="Adınız...">

        <label>E-Posta Adresiniz:</label>
        <input type="email" name="email" required placeholder="ornek@mail.com">

        <label>Mesajınız:</label>
        <textarea name="mesaj" rows="5" required placeholder="Mesajınızı buraya yazın..."></textarea>

        <button type="submit" name="btnGonder">GÖNDER</button>
    </form>
    
    <a href="giris-sayfasi1.php" class="back-link">← Giriş Sayfasına Dön</a>
</div>

</body>
</html>
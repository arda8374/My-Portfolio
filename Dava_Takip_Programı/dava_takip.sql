-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 31 Ara 2025, 15:31:13
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `dava_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `kullanici_adi`, `sifre`, `email`) VALUES
(1, 'admin', '1234', 'ozkula838@gmail.com');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adres_defteri`
--

CREATE TABLE `adres_defteri` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `kategori` varchar(50) DEFAULT 'Müvekkil'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `adres_defteri`
--

INSERT INTO `adres_defteri` (`id`, `ekleyen_id`, `ad_soyad`, `telefon`, `email`, `adres`, `kategori`) VALUES
(4, 1, 'Ahmet Yılmaz', '05462827378', 'ozkularda1@gmail.com', 'tepeköy mh. dergah sk. no:5 daire:3', 'Müvekkil'),
(5, 1, 'ARDA ÖZKUL', '05462827378', 'ozkularda1@gmail.com', 'tepeköy mh. dergah sk. no:5 daire:3', 'Müvekkil'),
(6, 1, 'Ahmet Yılmaz', '05462827377', 'ozkularda1@gmail.com', 'tepeköy mh. dergah sk. no:5 daire:3', 'Müvekkil');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `davalar`
--

CREATE TABLE `davalar` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `dosya_no` varchar(50) NOT NULL,
  `mahkeme` varchar(100) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `davaci` varchar(100) NOT NULL,
  `davali` varchar(100) NOT NULL,
  `konu` text DEFAULT NULL,
  `durum` varchar(50) DEFAULT 'Açık',
  `tarih` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `davalar`
--

INSERT INTO `davalar` (`id`, `ekleyen_id`, `dosya_no`, `mahkeme`, `kategori`, `davaci`, `davali`, `konu`, `durum`, `tarih`) VALUES
(1, 1, '2025/1', 'İstanbul 1. Asliye Hukuk Mahkemesi', NULL, 'Ahmet Yılmaz', 'Mehmet Demir', 'Alacak Davası', 'Açık', '0000-00-00 00:00:00'),
(5, 1, '2025/1', 'Ankara 4. Ağır Ceza Mahkemesi', 'Danışmanlık', 'Ahmet Yılmaz', 'Mehmet Demir', NULL, 'Karar Aşaması', '2025-12-21 00:00:00'),
(6, 1, '2025/1', 'konya 5. Ağır Ceza Mahkemesi', 'Danışmanlık', 'Ahmet Yılmaz', 'mehmet topuz', NULL, 'Açık', '2025-12-21 00:00:00'),
(7, 3, '2025/1', 'İstanbul 1. Asliye Hukuk Mahkemesi', 'Hukuk Davası', 'Ahmet Yılmaz', 'gsrzhtgck', NULL, 'Karar Aşaması', '2025-12-31 00:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `eposta`, `sifre`, `kayit_tarihi`) VALUES
(1, 'ARDA ÖZKUL', 'ozkularda1@gmail.com', '$2y$10$rnlYZWdQKn6IQGEOXe1gpurWq/.Ns2WuC1aSjV7AURis6RbgexfYa', '2025-12-21 11:48:04'),
(2, 'ARDA ÖZKUL', 'ozkula838@gmail.com', '$2y$10$fjNTpJ281rFDT41VHgCo4OCUCA4R0/FJXEWDZALHllfu0XridQvaq', '2025-12-31 02:09:26'),
(3, 'ARDA ÖZKUL', 'ozkularda2@gmail.com', '$2y$10$YbezFNm60o3Ba5m3CqbdL.GEXYGsJhUlzITZwqOX0Um.mPf5gacW.', '2025-12-31 02:55:20');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kutuphane`
--

CREATE TABLE `kutuphane` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `kitap_adi` varchar(255) NOT NULL,
  `yazar` varchar(150) DEFAULT NULL,
  `yayin_evi` varchar(150) DEFAULT NULL,
  `basim_yili` varchar(4) DEFAULT NULL,
  `durum` varchar(50) DEFAULT 'Ofiste',
  `notlar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `muvekkiller`
--

CREATE TABLE `muvekkiller` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `muvekkiller`
--

INSERT INTO `muvekkiller` (`id`, `ad_soyad`, `telefon`, `email`, `adres`, `kayit_tarihi`) VALUES
(1, 'ARDA ÖZKUL', '05462827378', 'ozkularda1@gmail.com', 'tepeköy mh. dergah sk. no:5 daire:3', '2025-12-18 02:12:45'),
(2, 'ARDA ÖZKUL', '05462827378', 'ozkularda1@gmail.com', 'tepeköy mh. dergah sk. no:5 daire:3', '2025-12-18 02:33:09');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `senetler`
--

CREATE TABLE `senetler` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `borclu` varchar(100) NOT NULL,
  `alacakli` varchar(100) NOT NULL,
  `tutar` decimal(15,2) NOT NULL,
  `vade_tarihi` date NOT NULL,
  `durum` varchar(50) DEFAULT 'Bekliyor',
  `notlar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `senetler`
--

INSERT INTO `senetler` (`id`, `ekleyen_id`, `borclu`, `alacakli`, `tutar`, `vade_tarihi`, `durum`, `notlar`) VALUES
(1, 1, 'alperen karaaca ', 'ahmet taha özkul', 20000000.00, '2025-12-21', 'İcrada', 'vermiyor'),
(2, 1, 'alperen karaaca ', 'ahmet taha özkul', 0.06, '2025-12-31', 'Bekliyor', 'yuo');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `smm_makbuzlar`
--

CREATE TABLE `smm_makbuzlar` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `muvekkil_adi` varchar(255) NOT NULL,
  `brut_tutar` decimal(10,2) NOT NULL,
  `stopaj_orani` int(11) DEFAULT 20,
  `kdv_orani` int(11) DEFAULT 20,
  `net_alinan` decimal(10,2) NOT NULL,
  `tarih` date NOT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `smm_makbuzlar`
--

INSERT INTO `smm_makbuzlar` (`id`, `ekleyen_id`, `muvekkil_adi`, `brut_tutar`, `stopaj_orani`, `kdv_orani`, `net_alinan`, `tarih`, `aciklama`) VALUES
(1, 1, 'alperen karaca', 99999999.99, 20, 20, 99999999.99, '2025-12-21', 'ehjykghşopö'),
(2, 1, 'alperen karaca', 300000.00, 20, 20, 300000.00, '2025-12-21', 'ödeme zamanında yapılacak '),
(3, 1, 'alperen karaca', 400000.00, 16, 27, 444000.00, '2025-12-21', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tanim_kategoriler`
--

CREATE TABLE `tanim_kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tanim_kategoriler`
--

INSERT INTO `tanim_kategoriler` (`id`, `kategori_adi`) VALUES
(3, 'Ceza Davası'),
(4, 'Hukuk Davası'),
(5, 'İcra Takibi'),
(6, 'İdari Yargı'),
(7, 'Danışmanlık');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tanim_mahkemeler`
--

CREATE TABLE `tanim_mahkemeler` (
  `id` int(11) NOT NULL,
  `mahkeme_adi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tanim_mahkemeler`
--

INSERT INTO `tanim_mahkemeler` (`id`, `mahkeme_adi`) VALUES
(3, 'İstanbul 1. Asliye Hukuk Mahkemesi'),
(4, 'Ankara 4. Ağır Ceza Mahkemesi'),
(5, 'İzmir 2. İcra Hukuk Mahkemesi'),
(6, 'Bursa Bölge Adliye Mahkemesi 5. Hukuk Dairesi'),
(7, 'İzmit 5. Ağır Ceza Mahkemesi'),
(8, 'Kandıra 5. Ağır ceza mahkemesi'),
(9, 'konya 5. Ağır Ceza Mahkemesi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vekaletnameler`
--

CREATE TABLE `vekaletnameler` (
  `id` int(11) NOT NULL,
  `ekleyen_id` int(11) NOT NULL,
  `muvekkil` varchar(100) NOT NULL,
  `noter` varchar(100) NOT NULL,
  `yevmiye_no` varchar(50) NOT NULL,
  `tarih` date NOT NULL,
  `notlar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `adres_defteri`
--
ALTER TABLE `adres_defteri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `davalar`
--
ALTER TABLE `davalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eposta` (`eposta`);

--
-- Tablo için indeksler `kutuphane`
--
ALTER TABLE `kutuphane`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `muvekkiller`
--
ALTER TABLE `muvekkiller`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `senetler`
--
ALTER TABLE `senetler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `smm_makbuzlar`
--
ALTER TABLE `smm_makbuzlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tanim_kategoriler`
--
ALTER TABLE `tanim_kategoriler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tanim_mahkemeler`
--
ALTER TABLE `tanim_mahkemeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `vekaletnameler`
--
ALTER TABLE `vekaletnameler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `adres_defteri`
--
ALTER TABLE `adres_defteri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `davalar`
--
ALTER TABLE `davalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `kutuphane`
--
ALTER TABLE `kutuphane`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `muvekkiller`
--
ALTER TABLE `muvekkiller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `senetler`
--
ALTER TABLE `senetler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `smm_makbuzlar`
--
ALTER TABLE `smm_makbuzlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `tanim_kategoriler`
--
ALTER TABLE `tanim_kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `tanim_mahkemeler`
--
ALTER TABLE `tanim_mahkemeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `vekaletnameler`
--
ALTER TABLE `vekaletnameler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

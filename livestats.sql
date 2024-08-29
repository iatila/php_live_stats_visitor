-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 18 Ağu 2024, 15:05:43
-- Sunucu sürümü: 10.4.33-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `livestats`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_liveStats` (IN `xurl` VARCHAR(155), IN `xpage` VARCHAR(555) CHARSET utf8, IN `xagent` VARCHAR(32), IN `xcountry` VARCHAR(3), IN `xip` VARCHAR(50), IN `xplatform` VARCHAR(30), IN `xbrowser` VARCHAR(50))  NO SQL BEGIN 

IF ( SELECT COUNT(lv_id) FROM live_stats WHERE lv_agent = xagent  AND NOW() < DATE_ADD(NOW(), INTERVAL 3 MINUTE) ) > 0 THEN 


           UPDATE live_stats SET 
    lv_url = xurl,
    lv_page = xpage,
    lv_date = DATE_ADD(NOW(), INTERVAL 3 MINUTE)
    WHERE lv_agent = xagent; 
    ELSE
    
  INSERT INTO live_stats
         (
           lv_url, lv_page, lv_agent, lv_date, lv_country, lv_ip, lv_platform, lv_browser                     
         )
    VALUES 
         ( 
           xurl, xpage, xagent, DATE_ADD(NOW(), INTERVAL 3 MINUTE), xcountry, xip, xplatform, xbrowser                 
         ) ; 

    END IF; 

END$$


DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `live_stats`
--

CREATE TABLE `live_stats` (
  `lv_id` int(11) NOT NULL,
  `lv_url` varchar(155) NOT NULL,
  `lv_page` varchar(555) NOT NULL,
  `lv_date` datetime NOT NULL,
  `lv_country` varchar(3) NOT NULL,
  `lv_ip` varchar(50) NOT NULL,
  `lv_platform` varchar(30) NOT NULL,
  `lv_browser` varchar(50) NOT NULL,
  `lv_agent` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tetikleyiciler `live_stats`
--
DELIMITER $$
CREATE TRIGGER `after_insert_live_stats` AFTER INSERT ON `live_stats` FOR EACH ROW BEGIN

IF NOT EXISTS (SELECT 1 FROM live_stats_website WHERE web_url = NEW.lv_url) THEN
    INSERT INTO live_stats_website (web_url,web_hit,web_date)
    VALUES (NEW.lv_url,1,NOW());
    ELSE
    UPDATE live_stats_website SET web_date = NOW(), web_hit = web_hit + 1 WHERE web_url = NEW.lv_url;
    
END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `live_stats_website`
--

CREATE TABLE `live_stats_website` (
  `web_id` int(11) NOT NULL,
  `web_url` varchar(155) NOT NULL,
  `web_hit` int(11) NOT NULL,
  `web_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `live_stats`
--
ALTER TABLE `live_stats`
  ADD PRIMARY KEY (`lv_id`);

--
-- Tablo için indeksler `live_stats_website`
--
ALTER TABLE `live_stats_website`
  ADD PRIMARY KEY (`web_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `live_stats`
--
ALTER TABLE `live_stats`
  MODIFY `lv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `live_stats_website`
--
ALTER TABLE `live_stats_website`
  MODIFY `web_id` int(11) NOT NULL AUTO_INCREMENT;

DELIMITER $$
--
-- Olaylar
--
CREATE DEFINER=`root`@`localhost` EVENT `ClearLiveVisitor` ON SCHEDULE EVERY 1 HOUR STARTS '2024-08-01 20:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Geçersiz ziyaretçileri temizler' DO DELETE FROM live_stats WHERE lv_date < NOW() - INTERVAL 7 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

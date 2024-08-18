# Online Ziyaretçi Takip Sistemi

Bu proje, PHP ile geliştirilmiş basit bir online ziyaretçi takip sistemidir. Web sitenizin ziyaretçilerini gerçek zamanlı olarak takip eder ve verileri bir veritabanında saklar.  `PHP 7` ve üzeri sürümlerde çalışır. 

## Özellikler

- **Gerçek Zamanlı İzleme**: Ziyaretçilerin web sitenizde hangi sayfalarda gezindiğini ve ne kadar süre kaldığını izler.
- **Veritabanı Entegrasyonu**: Ziyaretçi verilerini bir MySQL veritabanında saklar.
- **Esnek Yapılandırma**: Zaman aralıkları ve kullanıcı erişimi gibi ayarları kolayca yapılandırabilirsiniz.

## Dikkat Edilmesi Gerekenler

- **Tarayıcı ve İşletim Sistemi**: Bu bilgilerin doğru bir şekilde alınabilmesi için `get_browser` eklentisinin kurulu ve etkin olması gerekmektedir. `get_browser` eklentisini [bu linkten](https://www.php.net/manual/en/function.get-browser.php) temin edebilir ve nasıl kurulacağını öğrenebilirsiniz.
- **Ülke Bilgisi**: Ülke kodunu almak için sitelerin `cloudflare` üzerinden yönlendirilmesi gerekir. Alternatif olarak istediğiniz geo apisini entegre edip kullanabilirsiniz.
- **Sitelere Entegre Etme**: Sistemi sitenize entegre etmek için aşağıdaki script etiketini kullanabilirsiniz:
  ```html
  <script src="//site.com/stats/stats.js"></script>

## Kurulum

### Veritabanı Yapılandırması

Öncelikle, veritabanınızı oluşturmanız ve gerekli tablo yapısını ayarlamanız gerekiyor. Bunun için veritabanını oluşturduktan sonra `livestats.sql` dosyasını yüklemeniz yeterlidir. Aşağıda, veritabanı bağlantı yapılandırması ve ayarları yer almaktadır.

#### `src/config.php`

```php
<?php
// Veritabanı yapılandırması
$db = new Database(
    'localhost', // Veritabanı sunucusunun adresi
    'dbname',    // Veritabanı adı
    'user',      // Veritabanı kullanıcı adı
    'pass'       // Veritabanı şifresi
);

// Konfigürasyon ayarları
$cnf = [
    'time_js' => 5000, // Milisaniye cinsinden 5 saniye
    'time_db' => 1,    // DB Sorgularında kaç dakikalık veri çekilecek
    'user' => '',      // Giriş kullanıcı adı
    'pass' => ''       // Giriş şifresi
];

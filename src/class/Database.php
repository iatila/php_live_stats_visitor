<?php
/**
 * Database sınıfı, pdodan türetildi ve veritabanı işlemlerini gerçekleştirir.
 */
class Database
{

    /**
     * @var PDO|null PDO nesnesi
     */
    private $conn;


    /**
     * Database sınıfının kurucusu.
     */
    public function __construct($host, $dbname, $username, $password, $charset = 'utf8') {
        try {
            $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
            $this->conn->query('SET CHARACTER SET ' . $charset);
            $this->conn->query('SET NAMES ' . $charset);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo 'Bağlantı hatası: ' . $e->getMessage();
        }
    }

    /**
     * Bir prosedürü çağırır.
     *
     * @param string $website Website ana adresini verir
     * @param string $fullUrl Adres çubuğundaki full urlyi verir
     * @param string $hash Benzersiz hash üretir
     * @param string $country Ülke bilgisini ISO 3166 formatında alır
     * @param string $ip IP adresi (ipv6 dahil)
     * @param string $device Hangi cihazdan bağlandığını gösterir
     * @param string $browser Tarayıcı bilgisini verir
     * @return bool İşlem başarılıysa true, aksi halde false
     */
    public function callProcedure($website, $fullUrl, $hash, $country, $ip, $device, $browser) {
        try {
            $stmt = $this->conn->prepare("CALL sp_liveStats(?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $website);
            $stmt->bindParam(2, $fullUrl);
            $stmt->bindParam(3, $hash);
            $stmt->bindParam(4, $country);
            $stmt->bindParam(5, $ip);
            $stmt->bindParam(6, $device);
            $stmt->bindParam(7, $browser);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            // echo 'Hata: ' . $e->getMessage();
            return false;
        }
    }
}

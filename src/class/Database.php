<?php
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
    // PDO bağlantısını döndürür
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Toplu select sorgusu çalıştırır ve sonuçları döndürür.
     *
     * @param string $table Tablo adı
     * @param array $columns Seçilecek sütunlar
     * @param string $where WHERE koşulu
     * @param array $params Parametreler
     * @return array|false Sorgu sonuçları veya hata durumunda false
     */
    public function fetchStats($table, $columns, $where, $params = []) {
        try {
            $columnsStr = implode(', ', $columns);
            $query = "SELECT $columnsStr FROM $table WHERE $where";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
}

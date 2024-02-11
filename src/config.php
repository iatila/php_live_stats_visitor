<?php
// Veritabanı sınıfını içeren dosyayı dahil eder
require 'class/Database.php';

// Veritabanına bağlanmak için gerekli bilgileri içeren bir Database örneği oluşturur
$database = new Database(
    'localhost', // Veritabanı sunucusunun adresi
    'dbname',    // Veritabanı adı
    'dbuser',    // Veritabanı kullanıcı adı
    'dbpass'     // Veritabanı şifresi
);

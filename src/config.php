<?php
require __DIR__.'/class/Database.php';
require __DIR__.'/class/Utils.php';
require __DIR__.'/basic.php';
require __DIR__.'/arrays.php';

$db = new Database(
    'localhost', // Veritabanı sunucusunun adresi
    'dbname',    // Veritabanı adı
    'user',    // Veritabanı kullanıcı adı
    'pass'     // Veritabanı şifresi
);



$cnf = [
  'time_js' => 5000, // Milisaniye cinsinden 5 saniye
  'time_db' => 1, // DB Sorgularında kaç dakikalık veri çekilecek
  'user' =>    '', // Giriş kullanıcı adı
  'pass' =>   '' // Giriş şifre
];
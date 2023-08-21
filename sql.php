<?php
require 'vendor/autoload.php'; // Faker kütüphanesi

use Faker\Factory;

// Veritabanı bilgileri
$host = 'localhost';
$dbname = 'test_verisi';
$username = 'root';
$password = '';

try {
    // PDO nesnesi oluşturma
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Faker nesnesi oluşturma
    $faker = Factory::create();

    // Büyük veri ekleme işlemi için işlemi başlatma
    $pdo->beginTransaction();

    // Eklenecek veri sayısı
    $veriSayisi = 20000000; // 20 milyon

    // Veri eklemek için döngü
    for ($i = 1; $i <= $veriSayisi; $i++) {
        $nameSurname = $faker->name;
        $address = $faker->address;
        $email = $faker->email;
        $callNumber = $faker->phoneNumber;

        // SQL sorgusu
        $sql = "INSERT INTO veriler (name_surname, address, email, call_number) 
                VALUES (:name_surname, :address, :email, :call_number)";

        // Sorguyu hazırlama
        $stmt = $pdo->prepare($sql);

        // Parametreleri bağlama
        $stmt->bindParam(':name_surname', $nameSurname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':call_number', $callNumber);

        // Sorguyu çalıştırma
        $stmt->execute();
    }

    // İşlemi onaylama
    $pdo->commit();

    echo "Veriler başarıyla eklendi.";
} catch (PDOException $e) {
    // İşlemi geri al
    $pdo->rollback();

    echo "Hata: " . $e->getMessage();
}
?>
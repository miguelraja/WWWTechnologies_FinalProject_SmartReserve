<?php
    $host = 'sql211.byetcluster.com';
    $dbName = 'smartreserve';
    $username = 'if0_41987345';
    $password = 'Lolazo2005';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully to the database.";
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
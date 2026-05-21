<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
    }

    $sql = "DELETE FROM users 
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$_SESSION['user_id']])) {
        header("Location: register.php");
        exit;
    } else {
        echo "Error trying to delete the booking.";
    }
?>
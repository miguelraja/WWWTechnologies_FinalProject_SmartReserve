<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
        header("Location: login.php");
        exit;
    }

    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$booking_id, $user_id])) {
        header("Location: my_bookings.php");
        exit;
    } else {
        echo "Error trying to delete the booking.";
    }
?>
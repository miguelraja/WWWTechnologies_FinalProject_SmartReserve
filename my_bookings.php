<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT b.*, r.name as room_name, r.capacity
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE b.user_id = ?
            ORDER BY b.booking_date DESC, b.start_time DESC";

   $stmt = $pdo->prepare($sql);
   $stmt -> execute([$user_id]);
   $my_bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>My Bookings - SmartReserve</title>
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body>
        <div class="container">
             <a href="index.php" class="btn-back">Back to Rooms</a>
        </div>
        <div class="container-bookings">
            <h2>My bookings</h2>            
            <?php if (count($my_bookings) == 0): ?>
                <p style="margin-top:20px;">You have not booked any rooms yet.</p>
            <?php else: ?>
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>More options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_bookings as $b): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($b['room_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($b['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($b['start_time']); ?></td>
                                <td><?php echo htmlspecialchars($b['end_time']); ?></td>
                                <td>
                                    <a href="edit_booking.php?id=<?php echo $b['id']; ?>" class="btn-action btn-edit">Edit</a>
                                    <a href="delete_booking.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');" class="btn-action btn-delete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </body>
</html>
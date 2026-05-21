<?php
    session_start();
    require 'db_connection.php';

    if(!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
        header("Location: login.php");
        exit;
    }

    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $message = "";

    $sql = "SELECT b.*, r.name as room_name FROM bookings b
            JOIN rooms r on b.room_id = r.id
            WHERE b.id = ? and b.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch();

    if (!$booking){
        die("Booking not found or access denied.");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
        $start = $_POST['start_time'];
        $end = $_POST['end_time'];
        $room_id = $booking['room_id'];

        $sql_check = "SELECT * FROM bookings
                        WHERE room_id = ?
                        AND booking_date = ?
                        AND id != ? 
                        AND (start_time < ? AND end_time > ?";

        $check = $pdo->prepare($sql_check);
        $check->execute([$room_id, $date, $booking_id, $end, $start]);

        if($check->rowCount() > 0) {
            $messge = "<div class='error'>Sorry, the room is already booked for that new time.</div>";
        } else {
            $sql_update = "UPDATE bookings SET booking_date = ?, start_time = ?, end_time = ? WHERE id = ? AND user_id = ?";
            $update = $pdo->prepare($sql_update);

            if ($update->execute([$date, $start, $end, $booking_id, $user_id])) {
                $message = "<div class='success'> Booking uupdated successfully! <a href='my_bookings.php'>Go back</a></div>";
                $booking['booking_date'] = $date;
                $booking['start_time'] = $start;
                $booking['end_time'] = $end;
            }
        }
    
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Booking - SmartReserve</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Edit booking: <?php echo htmlspecialchars($booking['room_name']); ?> </h2>
            <?php echo $message; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" name="date" id="datePicker" value="<?php echo $booking['booking_date']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Start Time:</label>
                    <input type="time" name="start_time" value="<?php echo $booking['start_time']; ?>" required>
                </div>
                <div class="form-group">
                    <label>End Time:</label>
                    <input type="time" name="end_time" value="<?php echo $booking['end_time']; ?>" required>
                </div>
                <button type="submit" class="btn-reserve" style="border:none; cursor:pointer; width:100%;">Save Changes</button>
            </form>
            <br>
            <a href="my_bookings.php">Cancel</a>
        </div>

        <script>
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('datePicker').setAttribute('min, today');
        </script>
    </body>
</html>
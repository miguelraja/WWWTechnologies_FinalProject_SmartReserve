<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $room_id = $_GET['id'] ?? null;
    $message = "";

    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt -> execute([$room_id]);
    $room = $stmt -> fetch();

    if (!$room) {
        die("Room not found");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
        $start = $_POST['start_time'];
        $end = $_POST['end_time'];
        $user_id = $_SESSION['user_id'];

        $sql_check = "SELECT * FROM bookings 
                    WHERE room_id = ? 
                    AND booking_date = ? 
                    AND (start_time < ? AND end_time > ?)";
        $check = $pdo->prepare($sql_check);
        $check -> execute([$room_id, $date, $end, $start]);

        if ($check -> rowCount() > 0) {
            $message = "<div class='error'>Sorry, this room is already booked for that time.</div>";
        } else {
            $insert = $pdo -> prepare ("INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
            if ($insert -> execute([$user_id, $room_id, $date, $start, $end])) {
                $message = "<div class='success'>Booking confirmed! <a href='index.php'>Go back</a></div>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Book <?php echo $room['name']; ?></title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Booking: <?php echo htmlspecialchars($room['name']); ?></h2>
            <?php echo $message; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" name="date" id="datePicker" required>
                </div>
                <div class="form-group">
                    <label>Start time:</label>
                    <input type="time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label>End time:</label>
                    <input type="time" name="end_time" required>
                </div>
                <button type="submit" class="btn-reserve" style="border:none; cursor:pointer; width:100%;">Confirm booking</button>
            </form>
            <br>
            <a href="index.php" class="link-nav-white">Cancel</a>
        </div>

        <script>
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('datePicker').setAttribute('min', today);
        </script>
    </body>
    </html>

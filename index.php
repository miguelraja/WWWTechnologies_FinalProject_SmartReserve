<?php
    session_start();
    require 'db_connection.php';

    $sql = "SELECT * FROM rooms WHERE 1=1";
    $params = [];

    if (!empty($_GET['capacity'])) {
        $sql .= " AND capacity >= ?";
        $params[] = intval($_GET['capacity']);
    }

    if (!empty($_GET['date']) && !empty($_GET['start_time']) && !empty($_GET['end_time'])) {
        $date = $_GET['date'];
        $start = $_GET['start_time'];
        $end = $_GET['end_time'];

        $sql .= " AND id NOT IN (
            SELECT room_id FROM bookings 
            WHERE booking_date = ? 
            AND start_time < ? 
            AND end_time > ?
        )";
        
        $params[] = $date;
        $params[] = $end;
        $params[] = $start;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="with=device-width, initial-scale=1.0">
        <title>SmartReserve - Coworking Rooms </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>SmartReserve</h1>
            <?php if(isset($_SESSION['username'])): ?>
                <p>Welcome, <strong> <a href="my_profile.php" class="link-nav"><?php echo $_SESSION['username']; ?></a></strong>! | <a href="logout.php" class="link-nav">Logout</a></p>
                <a href="my_bookings.php" class="link-nav">My bookings</a>
            <?php else: ?>
                <p><a href="login.php" class="link-nav">Login</a> or <a href="register.php" class="link-nav">Register</a> to book.</p>
            <?php endif; ?>
            <p>Select a room to start your booking</p>
        </header>
        <div class="container">
            <h2>Filter Rooms</h2>
            <form action="index.php" method="GET" class="filter-form">
                <div class="filter-group-container">
                    <div class="filter-field">
                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                    </div>
                    <div class="filter-field">
                        <label for="start_time">Start Time:</label>
                        <input type="time" name="start_time" id="start_time" value="<?php echo isset($_GET['start_time']) ? htmlspecialchars($_GET['start_time']) : ''; ?>">
                    </div>
                    <div class="filter-field">
                        <label for="end_time">End Time:</label>
                        <input type="time" name="end_time" id="end_time" value="<?php echo isset($_GET['end_time']) ? htmlspecialchars($_GET['end_time']) : ''; ?>">
                    </div>
                    <div class="filter-field">
                        <label for="capacity">Minimum Capacity:</label>
                        <input type="number" name="capacity" id="capacity" min="1" value="<?php echo isset($_GET['capacity']) ? htmlspecialchars($_GET['capacity']) : ''; ?>" placeholder="e.g. 5">
                    </div>
                    <div class="filter-buttons">
                        <button type="submit" class="btn-action btn-edit" >Filter</button>
                        <a href="index.php" class="btn-clear">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="container">
            <h3>Available Rooms</h3>
            
            <div class="rooms-grid">
                <?php if (count($rooms) > 0): ?>
                    <?php foreach ($rooms as $room): ?>
                        
                        <div class="room-card">
                            <h4><?php echo htmlspecialchars($room['name']); ?></h4>
                            <div class="room-card-body">
                                <p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                                
                                <a href="reserve.php?id=<?php echo $room['id']; ?>&date=<?php echo $_GET['date'] ?? ''; ?>&start=<?php echo $_GET['start_time'] ?? ''; ?>&end=<?php echo $_GET['end_time'] ?? ''; ?>" class="btn-action btn-edit">Book Now</a>
                            </div>
                        </div>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1; color: #666;">No rooms available with those criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
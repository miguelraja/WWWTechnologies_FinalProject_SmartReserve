<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users
            WHERE id = ?";
    
    $stmt = $pdo -> prepare($sql);
    $stmt->execute([$user_id]);

    $user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="UTF-8">
        <title><?php echo $user['username']; ?>'s profile</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <a href="index.php" class="btn-back">Back to Rooms</a>
        </div>
        <div class="container">
            <h2>Profile information</h2>
        </div>        
        <div class="container">            
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Information</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Username:</strong> <?php echo $user['username']; ?></td>
                        <td>
                            <a href="edit_username.php?id=<?php echo $user['id']; ?>" class="btn-action btn-edit">Edit</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong> <?php echo $user['email']; ?></td>
                        <td>
                            <a href="edit_email.php?id=<?php echo $user['id']; ?>" class="btn-action btn-edit">Edit</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Password:</strong> <?php echo str_repeat('•', 8); ?></td>
                        <td>
                            <a href="edit_password.php?id=<?php echo $user['id']; ?>" class="btn-action btn-edit">Edit</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="max-width: 200px; width:100%; margin-left: 210px;">
            <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete your account?\nAll your bookings will also be deleted.');" class="btn-action btn-delete">Delete account</a>
        </div>
    </body>
</html>
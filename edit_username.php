<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
    }

    $user_id = $_SESSION['user_id'];
    $error = "";
    $success = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_username = trim($_POST['username']);

        if(!empty($new_username)){
            $sql = "UPDATE users SET username = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$new_username, $user_id])) {
                $success = "Username updated successfully!";
            } else {
                $error = "An error ocurred when updating the username.";
            }
        } else {
            $error = "Username field cannot be empty";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Username</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2>Change username</h2>
        </div>
        <div class="container">
            <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div style="color: green; margin-bottom: 10px;"><?php echo $success; ?></div>
                <?php endif; ?>

                <form action="edit_username.php" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label for="username">New username:</label>
                        <input type="text" name="username" id="username" required style="padding: 5px; width: 100%; max-width: 300px;">
            </div>
            <button type="submit" class="btn-action btn-edit" style="border: none; cursor: pointer;">Save changes</button>
            <a href="my_profile.php" class="btn-back" style="margin-left: 10px;">Cancel</a>
        </form>
        </div>
    </body>
</html>
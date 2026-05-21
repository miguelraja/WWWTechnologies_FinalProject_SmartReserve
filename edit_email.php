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
        $new_email = trim($_POST['email']);

        if(!empty($new_email)){
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$new_email, $user_id])) {
                $success = "Email updated successfully!";
            } else {
                $error = "An error ocurred when updating the Email.";
            }
        } else {
            $error = "Email field cannot be empty";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Email</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2>Change email</h2>
        </div>
        <div class="container">
            <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div style="color: green; margin-bottom: 10px;"><?php echo $success; ?></div>
                <?php endif; ?>

                <form action="edit_email.php" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label for="email">New email:</label>
                        <input type="text" name="email" id="email" required style="padding: 5px; width: 100%; max-width: 300px;">
            </div>
            <button type="submit" class="btn-action btn-edit" style="border: none; cursor: pointer;">Save changes</button>
            <a href="my_profile.php" class="btn-back" style="margin-left: 10px;">Cancel</a>
        </form>
        </div>
    </body>
</html>
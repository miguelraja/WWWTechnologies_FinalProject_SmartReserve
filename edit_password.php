<?php
    session_start();
    require 'db_connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
    }

    $user_id = $_SESSION['user_id'];
    $error = "";
    $success = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = trim($_POST['email']);
        $confirm_password = trim($_POST['confirm_password']);

        if (!empty($new_password) && !empty($confirm_password)){
            if ($new_password == $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$hashed_password, $user_id])) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "An error occured while changing the password.";
                }
            } else {
                $error = "Passwords do not match.";
            }
        } else {
            $error = "All field are required.";
        }
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Password</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2>Change password</h2>
            
            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div style="color: green; margin-bottom: 10px;"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="edit_password.php" method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="password">New password:</label>
                    <input type="password" name="password" id="password" required style="padding: 5px; width: 100%; max-width: 300px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="confirm_password">Confirm new password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required style="padding: 5px; width: 100%; max-width: 300px;">
                </div>
                <button type="submit" class="btn-action btn-edit" style="border: none; cursor: pointer;">Save changes</button>
                <a href="my_profile.php" class="btn-back" style="margin-left: 10px;">Cancel</a>
            </form>
        </div>
    </body>
</html>
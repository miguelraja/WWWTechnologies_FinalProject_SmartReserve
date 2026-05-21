<?php
    session_start();
    require 'db_connection.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login - SmartReserve</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div style="max-width: 400px; margin: 80px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<p style='color:red';>$error</p>"; ?>

            <form method="POST">
                <input type="email" name="email" placeholder="Email" required style="Width:90%; padding: 10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
                <input type="password" name="password" placeholder="Password" required style="width:90%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;">
                <button type="submit" class="btn-reserve" style="width:100%; border:none; cursor:pointer;">Enter</button>
            </form>
            <p style="margin-top:15px;"> New here? <a href="register.php">Create account</a></p>
        </div>
    </body>
</html>

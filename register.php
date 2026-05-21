<?php 
    require "db_connection.php";

    $message="";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if($stmt->execute([$username, $email, $hashed_password])) {
                $message = "<p class='success'> Registration successful! <a href='login.php'>Login</a></p>";
            }
        } catch (PDOException $e) {
            $message = "<p class='error'> Error: Username or email already taken.</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register - SmartReserve</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Create an account</h2>
            <?php echo $message; ?>

            <form id="regForm" method="POST" action="register.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_pass" placeholder="Confirm password" required>

                <button type="submit" class="btn-reserve" style="border:none; cursor:ponter;">Register now</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>

        <script>
            document.getElementById('regForm').onSubmit = function(e) {
                const pass = document.getElementById('password').value;
                const confirm = document.getElementById('confirm_pass').value;

                if (pass !== confirm){
                    alert("Passwords do not match!");
                    e.preventDefault();
                    return false;
                }
            };
        </script>
    </body>
    </html>

<?php
session_start();
?>

<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../employee11/resources/css/loginPage.css">
</head>

<body>
    <section>
        <div>
            <h3>Employee Login</h3>
        </div>

        <div class="login-container">
        <?php
        if (isset($_SESSION['msg'])) {
            echo '<p style="color:red; text-align:center;">' . $_SESSION['msg'] . '</p>';
            unset($_SESSION['msg']);
        }
        ?>
            <form action="../employee11/Controller/loginProcess.php" method="post">
                <div class="input-group">
                    <label for="email">Email Address</label>

                    <input type="email" id="email" name="email">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                        title="Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.">
                </div>
                <button type="submit">Login</button>
            </form>
            <div>
                <p>Don't have account.? <a href="../employee11/webpage/registration.php">Create Account</a></p>
            </div>
        </div>
    </section>
</body>

</html>
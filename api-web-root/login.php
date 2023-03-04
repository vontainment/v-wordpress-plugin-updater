<?php
// Check if the user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        $valid_username = 'admin';
        $valid_password = 'password';
        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
?>

        <html>

        <head>
            <title>API Update Admin Login</title>
            <style>
                /* Center the form in the middle of the page */
                body {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                }

                /* Style the box around the form */
                .login-box {
                    width: 400px;
                    padding: 20px;
                    border: 2px solid #2ecc71;
                    border-radius: 5px;
                    box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.47);
                    text-align: center;
                }

                .login-box form {
                    text-align: left;
                }

                input[type="text"] {
                    width: 100%;
                    padding: 5px;
                    border-radius: 5px;
                    border: 1px solid #ccc;
                    box-sizing: border-box;
                }

                input[type="password"] {
                    width: 100%;
                    padding: 5px;
                    border-radius: 5px;
                    border: 1px solid #ccc;
                    box-sizing: border-box;
                }

                input[type="submit"] {
                    background-color: #4CAF50;
                    color: white;
                    padding: 8px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }

                input[type="submit"]:hover {
                    background-color: #3e8e41;
                }

                /* Style the logo */
                .logo {
                    width: 300px;
                    height: 60px;
                    margin-bottom: 20px;
                }
            </style>
        </head>

        <body>
            <div class="login-box">
                <img src="logo.png" alt="Logo" class="logo">
                <h2>Login</h2>
                <form method="post">
                    <label>Username:</label>
                    <input type="text" name="username"><br><br>
                    <label>Password:</label>
                    <input type="password" name="password"><br><br>
                    <input type="submit" value="Log In">
                </form>
            </div>
        </body>

        </html>
<?php
    }
} else {
    // User is already logged in, redirect them to the homepage
    header('Location: index.php');
    exit();
}
?>
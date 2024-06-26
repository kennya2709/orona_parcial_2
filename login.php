<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logindb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Validar datos de entrada
    if (empty($user) || empty($pass)) {
        $message = "Username and password are required.";
    } else {
        // Preparar la consulta
        $sql = "SELECT id, password FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_password);
            $stmt->fetch();

            if ($pass === $db_password) {
                $message = "Login successful!";
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "No user found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #6a1b9a;
            font-size: 24px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
            text-align: center;
            font-size: 16px;
            background-color: #f3e5f5;
        }
        .login-container input[type="submit"] {
            background-color: #81d4fa;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .login-container input[type="submit"]:hover {
            background-color: #4fc3f7;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 16px;
        }
        .alert-success {
            background-color: #a5d6a7;
            color: white;
        }
        .alert-error {
            background-color: #ef9a9a;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if ($message): ?>
        <div class="alert <?php echo strpos($message, 'successful') !== false ? 'alert-success' : 'alert-error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" autocomplete="username" required>
        <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>

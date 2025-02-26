<?php

// set Anti-clickjacking headers
header(header: 'X-Frame-Options: SAMEORIGIN');

// Enable error logging
ini_set('log_errors', 1);
ini_set("error_log", "error.log");

// Fix Cookie without SameSite Attribute
session_set_cookie_params([ 'lifetime' => 0, 'path' => '/', 'domain' => '', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict' ]);
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;;

        // Input validation
        if (!empty($email) && !empty($password)) {
            // Prevent sql injection 
            $stmt = $conn->prepare("
                SELECT id, email, password, 'admin' AS role FROM tbl_admin WHERE email = ?
                UNION 
                SELECT id, employee_email AS email, employee_password AS password, 'employee' AS role FROM tbl_employees WHERE employee_email = ?
            ");
            if (!$stmt) {
                throw new Exception("Database prepare failed");
            }

            $stmt->bind_param("ss", $email, $email);
            if (!$stmt->execute()) {
                throw new Exception("Database execution failed");
            }
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // password verification
                if ($password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: admin_home.php"); 
                    } else {
                        header("Location: employee_home.php");
                    }
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "Invalid email or password.";
            }
            $stmt->close();
        } else {
            $error = "Please fill in all fields.";
        }
        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $error = "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VitaLink</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome to VitaLink</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
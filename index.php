<?php
// set Content Security Policy (CSP) Header 
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self' data:; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

// set Anti-clickjacking headers
header(header: 'X-Frame-Options: SAMEORIGIN');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaLink - Medical Supply Inventory & Distribution System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .welcome-banner {
            text-align: center;
            padding: 30px 0;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 40px 0;
        }
        .feature-card {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }
        .login-section {
            text-align: center;
            margin: 40px 0;
        }
        .login-btn {
            padding: 10px 30px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-banner">
            <h1>Welcome to VitaLink</h1>
            <h2>A Medical Supply Inventory & Distribution System</h2>
            <p>VitaLink is designed to help healthcare facilities manage medical supply inventory efficiently. With secure access for administrators and employees, you can track, update, and monitor supplies seamlessly.</p>
        </div>

        <div class="features">
            <div class="feature-card">
                <h3>Supply Chain Monitoring</h3>
                <p>Track medical inventory in real time.</p>
            </div>
            <div class="feature-card">
                <h3>Supplier Contract Management</h3>
                <p>Manage supplier contracts efficiently.</p>
            </div>
            <div class="feature-card">
                <h3>Employee Management</h3>
                <p>Admin can create and manage employee accounts.</p>
            </div>
            <div class="feature-card">
                <h3>Secure Access</h3>
                <p>Role-based login for Admins and Employees.</p>
            </div>
        </div>

        <div class="login-section">
            <form action="login.php" method="get">
                <button type="submit" class="login-btn">Login to VitaLink</button>
            </form>
        </div>

        <div class="footer">
            <p>© 2025 VitaLink. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

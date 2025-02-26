<?php
session_start();
require_once "db_connection.php";

// Ensure user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - VitaLink</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .dashboard {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h3 {
            color: #007bff;
            margin-top: 0;
        }
        .card p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        .card button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome-section">
            <h2>Admin Dashboard</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</p>
        </div>
        <form action="login.php" method="get">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="dashboard">
        <div class="card-container">
            <div class="card">
                <h3>Manage Supply Chain</h3>
                <p>Fully manage the medical supply inventory. The administrator can add new medical supplies, update existing quantities, change supply statuses, and delete outdated stock.</p>
                <form action="admin_supplychain.php" method="get">
                    <button type="submit">Access Supply Chain</button>
                </form>
            </div>

            <div class="card">
                <h3>Supplier Contract Management</h3>
                <p>Add new suppliers, edit supplier contact details, and manage contract start and end dates. Admin only access, confidential information.</p>
                <form action="admin_suppliers.php" method="get">
                    <button type="submit">Manage Suppliers</button>
                </form>
            </div>

            <div class="card">
                <h3>Manage Employee Accounts</h3>
                <p>Create new employee accounts, update employee details (such as contact information), reset passwords, and remove inactive employees.</p>
                <form action="admin_manageaccounts.php" method="get">
                    <button type="submit">Manage Employees</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();

// Ensure user is logged in and is an employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "employee") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Home - VitaLink</title>
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
            <h1>Employee Dashboard</h1>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>!</h2>
        </div>
        <form action="login.php" method="get">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="dashboard">
        <div class="card-container">
            <div class="card">
                <h3>Supply Chain Monitoring</h3>
                <p>View the list of available medical supplies along with key details such as item names, available stock, supplier names, supply status, and delivery progress.</p>
                <form action="employee_supplychain.php" method="get">
                    <button type="submit">View Supply Chain</button>
                </form>
            </div>

            <div class="card">
                <h3>Personal Information</h3>
                <p>Update your personal information, including your first name, last name, contact number, and home address. Employees can keep their contact details current, ensuring smooth communication within the system.</p>
                <form action="employee_editinfo.php" method="get">
                    <button type="submit">Edit Your Information</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

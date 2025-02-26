<?php
session_start();
require_once "db_connection.php";

// Ensure only admin can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$success_msg = $error_msg = "";

// CREATE: Add a new employee account
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_employee"])) {
    $employee_id = trim($_POST["employee_id"]);
    $employee_email = trim($_POST["employee_email"]);
    $employee_password = trim($_POST["employee_password"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $position = trim($_POST["position"]);
    $contact_num = trim($_POST["contact_num"]);
    $address = trim($_POST["address"]);

    if (!empty($employee_id) && !empty($employee_email) && !empty($employee_password) && !empty($firstname) && !empty($lastname) && !empty($position) && !empty($contact_num) && !empty($address)) {
        // Hash password securely
        $hashed_password = password_hash($employee_password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tbl_employees (employee_id, employee_email, employee_password, firstname, lastname, position, contact_num, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $employee_id, $employee_email, $hashed_password, $firstname, $lastname, $position, $contact_num, $address);

        if ($stmt->execute()) {
            $success_msg = "New employee added successfully.";
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
            $error_msg = "Error adding employee.";
        }
        $stmt->close();
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// READ: Fetch all employees
$sql = "SELECT * FROM tbl_employees";
$result = $conn->query($sql);

// UPDATE: Edit employee details (excluding password)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_employee"])) {
    $id = $_POST["id"];
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $position = trim($_POST["position"]);
    $contact_num = trim($_POST["contact_num"]);
    $address = trim($_POST["address"]);

    if (!empty($firstname) && !empty($lastname) && !empty($position) && !empty($contact_num) && !empty($address)) {
        $update_sql = "UPDATE tbl_employees SET firstname = ?, lastname = ?, position = ?, contact_num = ?, address = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $firstname, $lastname, $position, $contact_num, $address, $id);

        if ($update_stmt->execute()) {
            $success_msg = "Employee information updated successfully.";
        } else {
            $error_msg = "Error updating employee information.";
        }
        $update_stmt->close();
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// UPDATE PASSWORD: Only if admin changes employee password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_password"])) {
    $id = $_POST["id"];
    $new_password = trim($_POST["new_password"]);

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE tbl_employees SET employee_password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $id);

        if ($update_stmt->execute()) {
            $success_msg = "Password updated successfully.";
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
            $error_msg = "Error updating password.";
        }
        $update_stmt->close();
    } else {
        $error_msg = "New password cannot be empty.";
    }
}

// DELETE: Remove employee account
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_employee"])) {
    $id = $_POST["id"];

    $delete_sql = "DELETE FROM tbl_employees WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        $success_msg = "Employee account deleted successfully.";
        header("Location: ".$_SERVER['PHP_SELF']);
                exit();
    } else {
        $error_msg = "Error deleting employee account.";
    }
    $delete_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee Accounts - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Manage Employee Accounts (Admin)</h2>
            <a href="admin_home.php" class="back-btn">Back to Home</a>
        </div>

        <?php if ($success_msg) echo "<div class='message success'>$success_msg</div>"; ?>
        <?php if ($error_msg) echo "<div class='message error'>$error_msg</div>"; ?>

        <div class="form-section">
            <h3>Add New Employee</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Employee ID:</label>
                    <input type="text" name="employee_id" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="employee_email" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="employee_password" required>
                </div>

                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="firstname" required>
                </div>

                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="lastname" required>
                </div>

                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" required>
                </div>

                <div class="form-group">
                    <label>Contact Number:</label>
                    <input type="text" name="contact_num" required>
                </div>

                <div class="form-group">
                    <label>Address:</label>
                    <input type="text" name="address" required>
                </div>

                <button type="submit" name="add_employee">Add Employee</button>
            </form>
        </div>

        <h3>Employee Accounts</h3>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="">
                            <td><?php echo htmlspecialchars($row["employee_id"]); ?></td>
                            <td><?php echo htmlspecialchars($row["employee_email"]); ?></td>
                            <td><input type="text" name="firstname" value="<?php echo htmlspecialchars($row["firstname"]); ?>"></td>
                            <td><input type="text" name="lastname" value="<?php echo htmlspecialchars($row["lastname"]); ?>"></td>
                            <td><input type="text" name="position" value="<?php echo htmlspecialchars($row["position"]); ?>"></td>
                            <td><input type="text" name="contact_num" value="<?php echo htmlspecialchars($row["contact_num"]); ?>"></td>
                            <td><input type="text" name="address" value="<?php echo htmlspecialchars($row["address"]); ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <button type="submit" name="update_employee">Update</button>
                                <button type="submit" name="delete_employee" onclick="return confirm('Are you sure?');" style="background-color: #dc3545;">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
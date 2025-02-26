<?php
session_start();
require_once "db_connection.php";

// Ensure user is logged in and is an employee
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "employee") {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION["user_id"];
$success_msg = $error_msg = "";

// Fetch employee data
$sql = "SELECT employee_id, employee_email, firstname, lastname, position, contact_num, address FROM tbl_employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $contact_num = trim($_POST["contact_num"]);
    $address = trim($_POST["address"]);

    // Input validation
    if (!empty($firstname) && !empty($lastname) && !empty($contact_num) && !empty($address)) {
        $update_sql = "UPDATE tbl_employees SET firstname = ?, lastname = ?, contact_num = ?, address = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $firstname, $lastname, $contact_num, $address, $employee_id);
        
        if ($update_stmt->execute()) {
            $success_msg = "Information updated successfully.";
            $employee["firstname"] = $firstname;
            $employee["lastname"] = $lastname;
            $employee["contact_num"] = $contact_num;
            $employee["address"] = $address;
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
            $error_msg = "Error updating information.";
        }
        $update_stmt->close();
    } else {
        $error_msg = "All fields must be filled.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Your Information - VitaLink</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Edit Your Information</h2>
            <a href="employee_home.php" class="back-btn">Back to Home</a>
        </div>

        <?php if ($success_msg) echo "<div class='message success'>$success_msg</div>"; ?>
        <?php if ($error_msg) echo "<div class='message error'>$error_msg</div>"; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Employee ID:</label>
                <input type="text" value="<?php echo htmlspecialchars($employee["employee_id"]); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" value="<?php echo htmlspecialchars($employee["employee_email"]); ?>" readonly>
            </div>

            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($employee["firstname"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($employee["lastname"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Position:</label>
                <input type="text" value="<?php echo htmlspecialchars($employee["position"]); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contact_num" value="<?php echo htmlspecialchars($employee["contact_num"]); ?>" required>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($employee["address"]); ?>" required>
            </div>

            <button type="submit">Update Information</button>
        </form>
    </div>
</body>
</html>

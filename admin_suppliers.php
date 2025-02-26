<?php
session_start();
require_once "db_connection.php";

// Ensure only admin can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$success_msg = $error_msg = "";

// CREATE: Add a new supplier contract
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_supplier"])) {
    $supplier_name = trim($_POST["supplier_name"]);
    $contact_person = trim($_POST["contact_person"]);
    $supplier_email = trim($_POST["supplier_email"]);
    $supplier_number = trim($_POST["supplier_number"]);
    $contract_startdate = trim($_POST["contract_startdate"]);
    $contract_enddate = trim($_POST["contract_enddate"]);
    $contract_status = trim($_POST["contract_status"]);

    if (!empty($supplier_name) && !empty($contact_person) && !empty($supplier_email) && !empty($supplier_number) && !empty($contract_startdate) && !empty($contract_enddate) && !empty($contract_status)) {
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $contract_startdate) && preg_match("/^\d{4}-\d{2}-\d{2}$/", $contract_enddate)) {
            $sql = "INSERT INTO tbl_supplier (supplier_name, contact_person, supplier_email, supplier_number, contract_startdate, contract_enddate, contract_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $supplier_name, $contact_person, $supplier_email, $supplier_number, $contract_startdate, $contract_enddate, $contract_status);

            if ($stmt->execute()) {
                $success_msg = "New supplier added successfully.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                $error_msg = "Error adding supplier.";
            }
            $stmt->close();
        } else {
            $error_msg = "Invalid date format. Use YYYY-MM-DD.";
        }
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// READ: Fetch all suppliers
$sql = "SELECT id, supplier_name, contact_person, supplier_email, supplier_number, contract_startdate, contract_enddate, contract_status FROM tbl_supplier";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_supplier"])) {
    $id = $_POST["id"]; // Get the correct supplier ID
    $supplier_name = trim($_POST["supplier_name"]);
    $contact_person = trim($_POST["contact_person"]);
    $supplier_email = trim($_POST["supplier_email"]);
    $supplier_number = trim($_POST["supplier_number"]);
    $contract_startdate = trim($_POST["contract_startdate"]);
    $contract_enddate = trim($_POST["contract_enddate"]);
    $contract_status = trim($_POST["contract_status"]);

    if (!empty($supplier_name) && !empty($contact_person) && !empty($supplier_email) && !empty($supplier_number) && !empty($contract_startdate) && !empty($contract_enddate) && !empty($contract_status)) {
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $contract_startdate) && preg_match("/^\d{4}-\d{2}-\d{2}$/", $contract_enddate)) {
            $update_sql = "UPDATE tbl_supplier SET supplier_name = ?, contact_person = ?, supplier_email = ?, supplier_number = ?, contract_startdate = ?, contract_enddate = ?, contract_status = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssssi", $supplier_name, $contact_person, $supplier_email, $supplier_number, $contract_startdate, $contract_enddate, $contract_status, $id);

            if ($update_stmt->execute()) {
                $success_msg = "Supplier contract updated successfully.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                $error_msg = "Error updating supplier contract.";
            }
            $update_stmt->close();
        } else {
            $error_msg = "Invalid date format. Use YYYY-MM-DD.";
        }
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// DELETE: Remove supplier contract
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_supplier"])) {
    $id = $_POST["id"];

    $delete_sql = "DELETE FROM tbl_supplier WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        $success_msg = "Supplier contract deleted successfully.";
        header("Location: ".$_SERVER['PHP_SELF']);
                exit();
    } else {
        $error_msg = "Error deleting supplier contract.";
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
    <title>Supplier Contract Management - Admin</title>
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
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button[name="delete_supplier"] {
            background-color: #dc3545;
        }

        button[name="delete_supplier"]:hover {
            background-color: #c82333;
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
            <h2>Supplier Contract Management (Admin)</h2>
            <a href="admin_home.php" class="back-btn">Back to Home</a>
        </div>

        <?php if ($success_msg) echo "<div class='message success'>$success_msg</div>"; ?>
        <?php if ($error_msg) echo "<div class='message error'>$error_msg</div>"; ?>

        <div class="form-section">
            <h3>Add New Supplier</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Supplier Name:</label>
                    <input type="text" name="supplier_name" required>
                </div>

                <div class="form-group">
                    <label>Contact Person:</label>
                    <input type="text" name="contact_person" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="supplier_email" required>
                </div>

                <div class="form-group">
                    <label>Contact Number:</label>
                    <input type="text" name="supplier_number" required>
                </div>

                <div class="form-group">
                    <label>Contract Start Date (YYYY-MM-DD):</label>
                    <input type="text" name="contract_startdate" required>
                </div>

                <div class="form-group">
                    <label>Contract End Date (YYYY-MM-DD):</label>
                    <input type="text" name="contract_enddate" required>
                </div>

                <div class="form-group">
                    <label>Contract Status:</label>
                    <input type="text" name="contract_status" required>
                </div>

                <button type="submit" name="add_supplier">Add Supplier</button>
            </form>
        </div>

        <h3>Supplier Contracts</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="">
                            <td><?php echo htmlspecialchars($row["id"]); ?></td>
                            <td><input type="text" name="supplier_name" value="<?php echo htmlspecialchars($row["supplier_name"]); ?>"></td>
                            <td><input type="text" name="contact_person" value="<?php echo htmlspecialchars($row["contact_person"]); ?>"></td>
                            <td><input type="email" name="supplier_email" value="<?php echo htmlspecialchars($row["supplier_email"]); ?>"></td>
                            <td><input type="text" name="supplier_number" value="<?php echo htmlspecialchars($row["supplier_number"]); ?>"></td>
                            <td><input type="text" name="contract_startdate" value="<?php echo htmlspecialchars($row["contract_startdate"]); ?>"></td>
                            <td><input type="text" name="contract_enddate" value="<?php echo htmlspecialchars($row["contract_enddate"]); ?>"></td>
                            <td><input type="text" name="contract_status" value="<?php echo htmlspecialchars($row["contract_status"]); ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <button type="submit" name="update_supplier">Update</button>
                                <button type="submit" name="delete_supplier" onclick="return confirm('Are you sure?');">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
session_start();
require_once "db_connection.php";

// Ensure user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$success_msg = $error_msg = "";

// CREATE: Handle adding a new supply item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_supply"])) {
    $item_name = trim($_POST["item_name"]);
    $quantity = trim($_POST["quantity"]);
    $supplier_id = trim($_POST["supplier_id"]);
    $status = trim($_POST["status"]);
    $delivery_status = trim($_POST["delivery_status"]);

    if (!empty($item_name) && !empty($quantity) && !empty($supplier_id) && !empty($status) && !empty($delivery_status)) {
        $sql = "INSERT INTO tbl_supplychain (item_name, quantity, supplier_id, status, delivery_status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("siiss", $item_name, $quantity, $supplier_id, $status, $delivery_status);

        if ($stmt->execute()) {
            $success_msg = "New supply item added successfully.";
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
            $error_msg = "Error adding supply item.";
        }
        $stmt->close();
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// READ: Fetch all supply items
$sql = "SELECT s.item_name, s.quantity, s.supplier_id, sp.supplier_name, s.status, s.delivery_status 
        FROM tbl_supplychain s
        JOIN tbl_supplier sp ON s.supplier_id = sp.id";

$result = $conn->query($sql);

// UPDATE: Handle updating an existing supply item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_supply"])) {
    $id = $_POST["supplier_id"];
    $item_name = trim($_POST["item_name"]);
    $quantity = trim($_POST["quantity"]);
    $supplier_id = trim($_POST["supplier_id"]);
    $status = trim($_POST["status"]);
    $delivery_status = trim($_POST["delivery_status"]);

    if (!empty($item_name) && !empty($quantity) && !empty($supplier_id) && !empty($status) && !empty($delivery_status)) {
        $update_sql = "UPDATE tbl_supplychain SET item_name = ?, quantity = ?, supplier_id = ?, status = ?, delivery_status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sisssi", $item_name, $quantity, $supplier_id, $status, $delivery_status, $id);

        if ($update_stmt->execute()) {
            $success_msg = "Supply item updated successfully.";
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
            $error_msg = "Error updating supply item.";
        }
        $update_stmt->close();
    } else {
        $error_msg = "All fields must be filled.";
    }
}

// DELETE: Handle deleting a supply item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_supply"])) {
    $id = $_POST["id"];

    $delete_sql = "DELETE FROM tbl_supplychain WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        $success_msg = "Supply item deleted successfully.";
        header("Location: ".$_SERVER['PHP_SELF']);
                exit();
    } else {
        $error_msg = "Error deleting supply item.";
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
    <title>Supply Chain Monitoring - Admin</title>
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

        input[type="text"],
        input[type="number"] {
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
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button[name="delete_supply"] {
            background-color: #dc3545;
        }

        button[name="delete_supply"]:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Supply Chain Monitoring (Admin)</h2>
            <a href="admin_home.php" class="back-btn">Back to Home</a>
        </div>

        <?php if ($success_msg) echo "<div class='message success'>$success_msg</div>"; ?>
        <?php if ($error_msg) echo "<div class='message error'>$error_msg</div>"; ?>

        <div class="form-section">
            <h3>Add New Supply Item</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="item_name" required>
                </div>

                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" required>
                </div>

                <div class="form-group">
                    <label>Supplier ID:</label>
                    <input type="number" name="supplier_id" required>
                </div>

                <div class="form-group">
                    <label>Status:</label>
                    <input type="text" name="status" required>
                </div>

                <div class="form-group">
                    <label>Delivery Status:</label>
                    <input type="text" name="delivery_status" required>
                </div>

                <button type="submit" name="add_supply">Add Supply</button>
            </form>
        </div>

        <h3>Current Inventory</h3>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Supplier Name</th>
                    <th>Status</th>
                    <th>Delivery Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                        <td><?php echo htmlspecialchars($row["supplier_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td><?php echo htmlspecialchars($row["delivery_status"]); ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row["supplier_id"]; ?>">
                                <input type="text" name="item_name" value="<?php echo htmlspecialchars($row["item_name"]); ?>" required>
                                <input type="number" name="quantity" value="<?php echo htmlspecialchars($row["quantity"]); ?>" required>
                                <input type="number" name="supplier_id" value="<?php echo htmlspecialchars($row["supplier_id"]); ?>" required>
                                <input type="text" name="status" value="<?php echo htmlspecialchars($row["status"]); ?>" required>
                                <input type="text" name="delivery_status" value="<?php echo htmlspecialchars($row["delivery_status"]); ?>" required>
                                <button type="submit" name="update_supply">Update</button>
                            </form>

                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row["supplier_id"]; ?>">
                                <button type="submit" name="delete_supply" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
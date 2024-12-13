<?php
session_start();
include 'admin_menu.php'; // Include the sidebar menu
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $emp_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM employees WHERE id = '$emp_id'");
    $employee = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $pay_rate = $_POST['pay_rate']; // Get the pay rate from the form

    // Update the employee data, including the pay rate
    $conn->query("UPDATE employees SET first_name = '$first_name', last_name = '$last_name', email = '$email', 
                  address = '$address', phone = '$phone', role = '$role', pay_rate = '$pay_rate' 
                  WHERE id = '$emp_id'");

    header("Location: employee_management.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="edit_emp" style="margin-left: 250px;">
    <div class="container my-5">
        <h2>Edit Employee</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?php echo $employee['first_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?php echo $employee['last_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $employee['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo $employee['address']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $employee['phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" name="role">
                    <option value="Employee" <?php echo ($employee['role'] == 'Employee') ? 'selected' : ''; ?>>Employee</option>
                    <option value="Admin" <?php echo ($employee['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="pay_rate" class="form-label">Hourly Pay Rate</label>
                <input type="number" step="0.01" class="form-control" name="pay_rate" value="<?php echo $employee['pay_rate']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
</body>
</html>

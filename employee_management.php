
<?php
session_start();
include 'db.php';
include 'admin_menu.php'; // Include the sidebar menu
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header("Location: index.php");
    exit;
}

$employees = $conn->query("SELECT * FROM employees");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_emp'])) {
    $emp_id = $_POST['emp_id'];
    $conn->query("DELETE FROM employees WHERE id = '$emp_id'");
    header("Location: employee_management.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <!-- Sidebar Navigation (Same as before) -->
    <!-- ... -->

    <!-- Main Content -->
    <div class="emp_maganement" style="margin-left: 250px;">
        <div class="container my-5">
            <h2 class="mb-4">Employee Management</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <!-- Delete Form -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="emp_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_emp" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <a href="employee_report.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View Reports</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

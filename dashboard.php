<?php
session_start();
include 'admin_menu.php'; // Include the sidebar menu
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header("Location: index.php");
    exit;
}

$employees = $conn->query("SELECT * FROM employees");
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
    <!-- Sidebar Navigation -->
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-left">
        <div class="container-fluid flex-column" style="height: 100vh; width: 250px; background-color: #343a40; color: white;">
            <div class="sidebar-header text-center p-4">
                <h4 class="text-white">Admin Panel</h4>
            </div>
            <ul class="navbar-nav flex-column w-100">
                <li class="nav-item">
                    <a class="nav-link text-white py-3 px-4" href="dashboard.php">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-3 px-4" href="add_employee.php">
                        <i class="bi bi-person-plus"></i> Add Employee
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-3 px-4" href="employee_management.php">
                        <i class="bi bi-person-plus"></i> Employee Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-3 px-4" href="attendance.php">
                        <i class="bi bi-clock"></i> Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-3 px-4" href="reports.php">
                        <i class="bi bi-bar-chart-line"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger py-3 px-4" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav> -->

    <!-- Main Content -->
    <div class="dashboard" style="margin-left: 250px;">
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
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

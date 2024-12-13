<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Query the database for user with the provided email and role
    $result = $conn->query("SELECT * FROM employees WHERE email='$email' AND role='$role'");

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();

        // Redirect based on role
        if ($role === 'Admin') {
            header("Location: dashboard.php");  // Admin dashboard
        } elseif ($role === 'Employee') {
            header("Location: attendance.php");  // Employee page
        }
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Punch In-Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h2 class="text-center">Login</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Choose Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <?php if (isset($error)) echo "<p class='text-danger mt-3'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include 'db.php';

$emp_id = $_SESSION['user']['id'];

// Handle Punch In/Out Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    if ($action == 'punch_in') {
        // Insert punch-in time
        $conn->query("INSERT INTO attendance (emp_id, punch_in_time) VALUES ('$emp_id', NOW())");

        // End the session and redirect to index.php after punching in
        session_unset();  // Unset all session variables
        session_destroy();  // Destroy the session
        header("Location: index.php");  // Redirect to login page
        exit;
    } elseif ($action == 'punch_out') {
        // Update punch-out time
        $conn->query("UPDATE attendance SET punch_out_time = NOW() WHERE emp_id='$emp_id' AND punch_out_time IS NULL");

        // End the session and redirect to index.php after punching out
        session_unset();  // Unset all session variables
        session_destroy();  // Destroy the session
        header("Location: index.php");  // Redirect to login page
        exit;
    }
}

// Fetch Attendance History
$result = $conn->query("SELECT * FROM attendance WHERE emp_id='$emp_id' ORDER BY punch_in_time DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-punch {
            width: 48%;
        }
        .attendance-table {
            margin-top: 20px;
        }
        .attendance-table th, .attendance-table td {
            text-align: center;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Punch In/Out Section -->
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                <h4>Attendance System</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <form method="POST" class="d-inline">
                        <button type="submit" name="action" value="punch_in" class="btn btn-success btn-punch me-2">
                            <i class="bi bi-play-circle"></i> Punch In
                        </button>
                    </form>
                    <form method="POST" class="d-inline">
                        <button type="submit" name="action" value="punch_out" class="btn btn-danger btn-punch">
                            <i class="bi bi-stop-circle"></i> Punch Out
                        </button>
                    </form>
                </div>
                <p class="text-muted text-center">
                    Use the buttons above to log your attendance.
                </p>
            </div>
        </div>

        <!-- Attendance History Section -->
        <div class="card">
            <div class="card-header text-center bg-secondary text-white">
                <h4>Attendance History</h4>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table table-bordered attendance-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0) {
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $status = empty($row['punch_out_time']) ? 'In Progress' : 'Completed';
                                    $statusClass = empty($row['punch_out_time']) ? 'warning' : 'success';
                                    echo "
                                        <tr>
                                            <td>{$count}</td>
                                            <td>{$row['punch_in_time']}</td>
                                            <td>" . ($row['punch_out_time'] ?? 'N/A') . "</td>
                                            <td><span class='badge bg-$statusClass'>$status</span></td>
                                        </tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted'>No attendance records found.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

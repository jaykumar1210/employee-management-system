<?php
include 'db.php';
include 'admin_menu.php'; // Include the sidebar menu
if (isset($_GET['id'])) {
    $emp_id = $_GET['id'];
    $result = $conn->query("SELECT e.name, a.punch_in_time, a.punch_out_time 
                            FROM attendance a 
                            JOIN employees e ON a.emp_id = e.id 
                            WHERE a.emp_id = '$emp_id' 
                            ORDER BY a.punch_in_time DESC");
}

function calculateHoursWorked($punch_in_time, $punch_out_time) {
    $punch_in = new DateTime($punch_in_time);
    $punch_out = new DateTime($punch_out_time);
    $interval = $punch_in->diff($punch_out);
    return $interval->format('%h hours %i minutes');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">

    <!-- Sidebar Navigation (Same as before) -->
    <!-- ... -->

    <!-- Main Content -->
    <div class="Emp_reports" style="margin-left: 250px;">
        <div class="container my-5">
            <h2 class="mb-4">Attendance Report</h2>
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Punch In</th>
                        <th>Punch Out</th>
                        <th>Total Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        // Get date
                        $date = new DateTime($row['punch_in_time']);
                        $total_hours = calculateHoursWorked($row['punch_in_time'], $row['punch_out_time']);
                    ?>
                    <tr>
                        <td><?php echo $date->format('Y-m-d'); ?></td>
                        <td><?php echo $date->format('H:i'); ?></td>
                        <td><?php echo (new DateTime($row['punch_out_time']))->format('H:i'); ?></td>
                        <td><?php echo $total_hours; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php
include 'db.php';
include 'admin_menu.php'; // Include the sidebar menu

// Default query for all records
$query = "SELECT e.name, a.punch_in_time, a.punch_out_time, e.pay_rate 
          FROM attendance a 
          JOIN employees e ON a.emp_id = e.id 
          WHERE 1";

// Handle filters
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_date = $_POST['from_date'] ?? null;
    $to_date = $_POST['to_date'] ?? null;

    if (!empty($from_date) && !empty($to_date)) {
        $query .= " AND DATE(a.punch_in_time) BETWEEN '$from_date' AND '$to_date'";
    } elseif (isset($_POST['two_weeks_from_date']) && !empty($_POST['two_weeks_start_date'])) {
        $start_date = $_POST['two_weeks_start_date'];
        $end_date = date('Y-m-d', strtotime($start_date . ' +14 days'));
        $query .= " AND DATE(a.punch_in_time) BETWEEN '$start_date' AND '$end_date'";
    }
}

$query .= " ORDER BY a.punch_in_time DESC";
$result = $conn->query($query);

function calculateHoursWorked($punch_in_time, $punch_out_time) {
    $punch_in = new DateTime($punch_in_time);
    $punch_out = new DateTime($punch_out_time);
    $interval = $punch_in->diff($punch_out);
    return $interval->format('%h') + ($interval->format('%i') / 60); // Return hours as a float
}

function getDayOfWeek($date) {
    $date = new DateTime($date);
    return $date->format('l');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <!-- Main Content -->
    <div class="Reports" style="margin-left: 250px;">
        <div class="container my-5">
            <h2 class="mb-4">Attendance Reports</h2>
            
            <!-- Filters Section -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" id="from_date" name="from_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" id="to_date" name="to_date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                </div>
            </form>
            
            <!-- Two Weeks Report -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="two_weeks_start_date" class="form-label">Start Date for 2 Weeks</label>
                    <input type="date" id="two_weeks_start_date" name="two_weeks_start_date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="two_weeks_from_date" value="1" class="btn btn-secondary">Generate 2 Weeks Report</button>
                </div>
            </form>
            
            <!-- Reports Table -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Total Hours</th>
                        <th>Hourly Rate</th>
                        <th>Total Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Initialize array to store employee data
                    $employees = [];
                    
                    // Fetch data and process
                    while ($row = $result->fetch_assoc()) {
                        $employee_name = $row['name'];
                        $date = new DateTime($row['punch_in_time']);
                        $day_of_week = $date->format('l'); // Get day of the week
                        
                        // Calculate hours worked for this record
                        $hours_worked = calculateHoursWorked($row['punch_in_time'], $row['punch_out_time']);
                        
                        // If employee doesn't exist in the array, add them
                        if (!isset($employees[$employee_name])) {
                            $employees[$employee_name] = [
                                'hours' => [
                                    'Sunday' => 0,
                                    'Monday' => 0,
                                    'Tuesday' => 0,
                                    'Wednesday' => 0,
                                    'Thursday' => 0,
                                    'Friday' => 0,
                                    'Saturday' => 0
                                ],
                                'total_hours' => 0,
                                'hourly_rate' => $row['pay_rate'], // Get hourly rate from database
                                'total_pay' => 0
                            ];
                        }

                        // Add hours worked to the correct day of the week
                        $employees[$employee_name]['hours'][$day_of_week] += $hours_worked;
                    }

                    // Now print the aggregated data
                    foreach ($employees as $name => $data) {
                        $total_hours = array_sum($data['hours']);
                        $hourly_rate = $data['hourly_rate'];
                        $total_pay = $total_hours * $hourly_rate;
                    ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo number_format($data['hours']['Sunday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Monday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Tuesday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Wednesday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Thursday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Friday'], 2); ?></td>
                        <td><?php echo number_format($data['hours']['Saturday'], 2); ?></td>
                        <td><?php echo number_format($total_hours, 2); ?></td>
                        <td><?php echo number_format($hourly_rate, 2); ?></td>
                        <td><?php echo number_format($total_pay, 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

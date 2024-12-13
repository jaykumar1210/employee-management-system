<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php'; // Database connection

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO employees (first_name, last_name, email, phone, address) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "New employee added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

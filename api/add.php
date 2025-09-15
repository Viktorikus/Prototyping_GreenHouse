<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $light_level = $_POST['light_level'];
    $lamp_status = $_POST['lamp_status'];
    
    $stmt = $conn->prepare("INSERT INTO sensor_data (temperature, humidity, light_level, lamp_status) VALUES ('$temperature', '$humidity', '$light_level', '$lamp_status')");

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menambahkan data"]);
    }

    $stmt->close();
}

$conn->close();
?>

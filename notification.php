<?php
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php';

// Ensure only admin users can access this endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $notification = trim(file_get_contents("php://input"));
    $data = json_decode($notification, true);

    if (!empty($data['notification'])) {
        $query = "INSERT INTO notification (notification) VALUES (:notification)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['notification' => $data['notification']]);
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Notification cannot be empty."]);
    }
    exit;


}
?>
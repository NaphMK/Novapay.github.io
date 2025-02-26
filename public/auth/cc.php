<?php
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php';

// Fetch the latest notification
$query = "SELECT notification FROM notification ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($query);
$notification = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>s
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            <?php if (!empty($notification)): ?>
                // Display the notification using SweetAlert
                Swal.fire({
                    title: 'Notification',
                    text: <?= json_encode($notification) ?>,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>

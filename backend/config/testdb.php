<?php
require_once 'C:\xampp\htdocs\vtu\k-wd-dashboard\backend\config\db.php';

if ($pdo) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>

<?php
require_once __DIR__ . "/../config/db.php";

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM queries WHERE status='unread'");
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo "<span style='background:red;color:white;padding:3px 8px;border-radius:50%;'>
            {$row['total']}
          </span>";
}
?>
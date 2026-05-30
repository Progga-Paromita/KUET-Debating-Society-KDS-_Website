<?php
session_start();

echo "<h2>SESSION TEST</h2>";

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['role'])) {
    echo "✅ Session WORKING. Logged in as: " . $_SESSION['role'];
} else {
    echo "❌ No session found";
}
?>
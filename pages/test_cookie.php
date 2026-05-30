<?php

echo "<h2>COOKIE TEST</h2>";

echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

if (isset($_COOKIE['role'])) {
    echo "✅ Cookie WORKING. Role: " . $_COOKIE['role'];
} else {
    echo "❌ No cookie found";
}
?>
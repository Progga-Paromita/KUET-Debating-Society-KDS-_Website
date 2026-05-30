<?php
session_destroy();

// remove cookies
setcookie("role", "", time() - 3600);
setcookie("user_id", "", time() - 3600);

header("Location: index.php?page=home");
exit;
?>
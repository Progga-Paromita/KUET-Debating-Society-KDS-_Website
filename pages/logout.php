<?php
session_start();
session_unset();
session_destroy();

setcookie("role", "", time() - 3600, "/");
setcookie("user_id", "", time() - 3600, "/");

header("Location: index.php?page=login");
exit;
?>
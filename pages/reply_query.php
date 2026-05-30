<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

/* CHECK IF FORM SUBMITTED */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // GET DATA SAFELY
    $id = (int) ($_POST['id'] ?? 0);
    $reply = trim($_POST['reply'] ?? '');

    if ($id > 0 && !empty($reply)) {

        // SAVE REPLY + MARK AS READ
        $stmt = $conn->prepare("
            UPDATE queries 
            SET reply=?, status='read' 
            WHERE id=?
        ");

        $stmt->bind_param("si", $reply, $id);
        $stmt->execute();

        // REDIRECT BACK WITH SUCCESS
        header("Location: index.php?page=admin_queries&replied=1");
        exit;

    } else {
        echo "<p style='color:red;'>Invalid request</p>";
    }
} else {
    echo "<p style='color:red;'>Invalid access</p>";
}
?>
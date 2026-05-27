<?php
require_once __DIR__ . "/config/db.php";

// Ensure session started (db.php might not start session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if member is logged in
if (!isset($_SESSION['member_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: index.php?page=role_select_login");
    exit;
}

$member_id = (int)$_SESSION['member_id'];
$member_data = [
    'full_name' => '',
    'roll' => '',
    'email' => '',
    'phone' => '',
    'dept' => '',
    'semester' => ''
];

$stmt = mysqli_prepare(
    $connection,
    "SELECT id, full_name, roll, email, phone, dept, semester FROM kds_db.member_db WHERE id = ? LIMIT 1"
);
?>


if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $member_data = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);
}
?>

<div class="profile-container">
    <div class="profile-card">


        <div class="profile-header">
            <h1>Member Profile</h1>
            <a href="index.php" class="btn">Back Home</a>
        </div>


        <div class="profile-grid">

            <div class="profile-box">
                <label>Full Name</label>
                <p><?= htmlspecialchars($member_data['full_name']) ?></p>
            </div>

            <div class="profile-box">
                <label>Roll</label>
                <p><?= htmlspecialchars($member_data['roll']) ?></p>
            </div>

            <div class="profile-box">
                <label>Email</label>
                <p><?= htmlspecialchars($member_data['email']) ?></p>
            </div>

            <div class="profile-box">
                <label>Phone</label>
                <p><?= htmlspecialchars($member_data['phone']) ?></p>
            </div>

            <div class="profile-box">
                <label>Department</label>
                <p><?= htmlspecialchars($member_data['dept']) ?></p>
            </div>

            <div class="profile-box">
                <label>Semester</label>
                <p><?= htmlspecialchars($member_data['semester']) ?></p>
            </div>

        </div>

        

    </div>
</div>
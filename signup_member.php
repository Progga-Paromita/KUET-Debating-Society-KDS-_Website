<?php
require_once __DIR__ . "/config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$full_name = "";
$preferred_name = "";
$roll = "";
$email = "";
$phone = "";
$dept = "";
$semester = "";
$password = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name'] ?? "");
    $preferred_name = trim($_POST['preferred_name'] ?? "");
    $roll = trim($_POST['roll'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $dept = trim($_POST['dept'] ?? "");
    $semester = trim($_POST['semester'] ?? "");
    $password = $_POST['password'] ?? "";

    if ($full_name && $preferred_name && $roll && $email && $phone && $dept && $semester && $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO kds_db.member_db
            (full_name, preferred_name, roll, email, phone, dept, semester, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssss",
                $full_name,
                $preferred_name,
                $roll,
                $email,
                $phone,
                $dept,
                $semester,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "✅ Registration successful! You can now log in.";
            } else {
                $error = "❌ Registration failed: " . mysqli_error($connection);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "❌ Database error. Please try again.";
        }
    } else {
        $error = "❌ Please fill all required fields.";
    }
}
?>

<div class="member-register-wrap">
    <div class="member-register-card">
        <div class="member-register-header">
            <div class="member-register-badge">
                <i class="fa-solid fa-graduation-cap"></i>
                Member Registration
            </div>
            <h2>Join as a Member</h2>
            <p class="member-register-subtitle">Create your member account. Your profile will appear after login.</p>
        </div>

        <?php if ($error): ?>
            <div class="member-register-alert member-register-alert-error" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <div class="member-register-alert-title">Error</div>
                    <div><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="member-register-alert member-register-alert-success" role="alert">
                <i class="fa-solid fa-check-circle"></i>
                <div>
                    <div class="member-register-alert-title">Success</div>
                    <div><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <form class="member-register-form" method="POST">
            <div class="member-register-grid">
                <div class="field field-full">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Preferred Name</label>
                    <input type="text" name="preferred_name" value="<?php echo htmlspecialchars($preferred_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Roll</label>
                    <input type="text" name="roll" value="<?php echo htmlspecialchars($roll, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field field-full">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Department</label>
                    <input type="text" name="dept" value="<?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Semester</label>
                    <input type="text" name="semester" value="<?php echo htmlspecialchars($semester, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field field-full">
                    <label>Password</label>
                    <input type="password" name="password" value="" required>
                </div>
            </div>

            <div class="member-register-actions">
                <button class="btn-primary role-submit-btn member-register-submit" type="submit">Create Account</button>
                <div class="member-register-hint">
                    Already have an account? <a href="index.php?page=role_select_login" style="color: var(--green-dark); text-decoration: none; font-weight: 700;">Log in</a>
                </div>
            </div>
        </form>

        <div class="member-register-benefits" style="margin-top: 18px;">
            <div class="benefit"><i class="fa-solid fa-shield"></i> Your password is stored securely (hashed).</div>
            <div class="benefit"><i class="fa-solid fa-id-card"></i> We show your details on the profile page after login.</div>
        </div>
    </div>
</div>

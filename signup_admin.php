<section style="height: 150px;"></section>
<?php
require_once __DIR__ . "/config/db.php";

$full_name = "";
$preferred_name = "";
$role = "";
$email = "";
$phone = "";
$password = "";
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST['full_name'] ?? "";
    $preferred_name  = $_POST['preferred_name'] ?? "";
    $role = $_POST['role'] ?? "";
    $email  = $_POST['email'] ?? "";
    $phone = $_POST['phone'] ?? "";
    $password  = $_POST['password'] ?? "";

    if (
        $full_name != "" &&
        $preferred_name != "" &&
        $role != "" &&
        $email != "" &&
        $phone != "" &&
        $password != ""
    ) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO admin_db
            (full_name, preferred_name, role, email, phone, password)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        // ✅ CHECK FIRST
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $full_name,
            $preferred_name,
            $role,
            $email,
            $phone,
            $hashed_password
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "✅ Data inserted successfully!";
        } else {
            $success = "❌ Error: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
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
                    <label>Role</label>
                    <input type="text" name="role" value="<?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field field-full">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="field">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
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
     </div>
</div>



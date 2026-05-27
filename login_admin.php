<?php
require_once __DIR__ . "/config/db.php";

$email = "";
$password = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";

    if ($email != "" && $password != "") {
        // Query to find admin by email
        $stmt = mysqli_prepare(
            $connection,
            "SELECT id, full_name, email, password FROM kds_db.admin_db WHERE email = ? LIMIT 1"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $admin = mysqli_fetch_assoc($result);

                // Verify password using password_verify
                if (password_verify($password, $admin['password'])) {
                    // Login successful - set session
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['full_name'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['role'] = 'admin';
                    
                    $success = "✅ Login successful! Redirecting...";
                    // Redirect after success
                    header("Location: index.php?page=profile_admin");
                    exit;
                } else {
                    $error = "❌ Invalid email or password.";
                }
            } else {
                $error = "❌ Invalid email or password.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "❌ Database error. Please try again.";
        }
    } else {
        $error = "❌ Please fill in all fields.";
    }
}
?>

<div style="padding: 40px; max-width: 500px; margin: 0 auto;">
    <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333; margin-top: 0;">Admin Login</h2>

        <?php if (!empty($error)): ?>
            <div style="padding: 12px; margin-bottom: 20px; background: #fee; color: #c33; border: 1px solid #fcc; border-radius: 4px; text-align: center;">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="padding: 12px; margin-bottom: 20px; background: #efe; color: #3c3; border: 1px solid #cfc; border-radius: 4px; text-align: center;">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; color: #555; font-weight: bold;">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; color: #555; font-weight: bold;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">Login</button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            <p>Don't have an account? <a href="index.php?page=role_select_signin" style="color: #667eea; text-decoration: none;">Sign Up</a></p>
        </div>
    </div>
</div>

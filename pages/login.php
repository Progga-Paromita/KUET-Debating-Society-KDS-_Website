<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once "config/db.php";

/* =========================
   SESSION CHECK
========================= */
if (isset($_SESSION['role'])) {
    header("Location: index.php?page=profile_" . $_SESSION['role']);
    exit;
}

/* =========================
   COOKIE LOGIN (SAFE)
========================= */
if (!isset($_SESSION['role']) && isset($_COOKIE['role']) && isset($_COOKIE['user_id'])) {

    $role = $_COOKIE['role'];
    $user_id = (int) $_COOKIE['user_id'];

    if ($role === "admin") {
        $stmt = $conn->prepare("SELECT id FROM admin WHERE id=?");
    } else {
        $stmt = $conn->prepare("SELECT id FROM member WHERE id=?");
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $user_id;

        header("Location: index.php?page=profile_" . $role);
        exit;
    }
}

$error = "";

/* =========================
   LOGIN PROCESS
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    /* =========================
       ADMIN LOGIN
    ========================= */
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {

        $_SESSION['role'] = "admin";
        $_SESSION['user_id'] = $admin['id'];

        if ($remember) {
            setcookie("role", "admin", time() + (7 * 24 * 60 * 60), "/", "", false, true);
            setcookie("user_id", $admin['id'], time() + (7 * 24 * 60 * 60), "/", "", false, true);
        }

        header("Location: index.php?page=profile_admin");
        exit;
    }

    /* =========================
       MEMBER LOGIN
    ========================= */
    $stmt = $conn->prepare("SELECT * FROM member WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $member = $stmt->get_result()->fetch_assoc();

    if ($member && password_verify($password, $member['password'])) {

        $_SESSION['role'] = "member";
        $_SESSION['user_id'] = $member['id'];

        if ($remember) {
            setcookie("role", "member", time() + (7 * 24 * 60 * 60), "/", "", false, true);
            setcookie("user_id", $member['id'], time() + (7 * 24 * 60 * 60), "/", "", false, true);
        }

        header("Location: index.php?page=profile_member");
        exit;
    }

    $error = "Invalid email or password!";
}
?>

<!-- =========================
     LOGIN UI
========================= -->

<section style="height: 300px;"></section>

<link rel="stylesheet" href="login.css">

<div class="member-register-wrap" style="padding-top: 70px;">
  <div class="member-register-card">

    <div class="member-register-header">
      <div class="member-register-badge">🔐 Welcome Back</div>
      <h2>Login</h2>
      <div class="member-register-subtitle">
        Sign in to access your dashboard
      </div>
    </div>

    <form class="member-register-form" method="POST">

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Your password" required>
      </div>

      <div class="form-group">
        <label style="display:flex; gap:10px; align-items:center;">
          <input type="checkbox" name="remember">
          Remember Me
        </label>
      </div>

      <div class="member-register-actions">
        <button type="submit" class="btn btn-primary">
          ➜ Login
        </button>
      </div>

      <?php if (!empty($error)) { ?>
        <div style="margin-top:12px; color:red; font-weight:600;">
          <?= $error ?>
        </div>
      <?php } ?>

    </form>
  </div>
</div>
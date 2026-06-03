<?php
// START SESSION (must be included via db.php or here)
if (!isset($_SESSION)) {
    session_start();
}

/* =========================
   COOKIE AUTO LOGIN FIRST
========================= */
if (!isset($_SESSION['role']) && isset($_COOKIE['role']) && isset($_COOKIE['user_id'])) {

    $_SESSION['role'] = $_COOKIE['role'];
    $_SESSION['user_id'] = $_COOKIE['user_id'];

    header("Location: index.php?page=profile_" . $_SESSION['role']);
    exit;
}

/* =========================
   SESSION CHECK
========================= */
if (isset($_SESSION['role'])) {
    header("Location: index.php?page=profile_" . $_SESSION['role']);
    exit;
}

/* =========================
   LOGIN PROCESS
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    /* =========================
       CHECK ADMIN
    ========================= */
    $admin = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
    $a = mysqli_fetch_assoc($admin);

    if ($a && password_verify($password, $a['password'])) {

        $_SESSION['role'] = "admin";
        $_SESSION['user_id'] = $a['id'];

        if ($remember) {
            setcookie("role", "admin", time() + (7 * 24 * 60 * 60));
            setcookie("user_id", $a['id'], time() + (7 * 24 * 60 * 60));
        }

        header("Location: index.php?page=profile_admin");
        exit;
    }

    /* =========================
       CHECK MEMBER
    ========================= */
    $member = mysqli_query($conn, "SELECT * FROM member WHERE email='$email'");
    $m = mysqli_fetch_assoc($member);

    if ($m && password_verify($password, $m['password'])) {

        $_SESSION['role'] = "member";
        $_SESSION['user_id'] = $m['id'];

        if ($remember) {
            setcookie("role", "member", time() + (7 * 24 * 60 * 60));
            setcookie("user_id", $m['id'], time() + (7 * 24 * 60 * 60));
        }

        header("Location: index.php?page=profile_member");
        exit;
    }

    echo "Invalid login!";
}
?>


<section style="height: 300px;"></section>

<div class="member-register-wrap" style="padding-top: 70px;">
  <div class="member-register-card">
    <div class="member-register-header">
      <div class="member-register-badge">🔐 Welcome Back</div>
      <h2 style="margin-bottom: 6px;">Login</h2>
      <div class="member-register-subtitle">Sign in to access your dashboard and saved updates.</div>
    </div>

    <form class="member-register-form" method="POST">
      <div class="member-register-grid" style="grid-template-columns: 1fr; margin-top: 0;">
        <div class="form-group" style="display:flex; flex-direction:column; gap:8px;">
          <label for="email">Email</label>
          <input id="email" name="email" placeholder="you@example.com" type="email" required>
        </div>

        <div class="form-group" style="display:flex; flex-direction:column; gap:8px;">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Your password" required>
        </div>

        <div class="form-group" style="display:flex; flex-direction:column; gap:10px;">
          <label style="display:flex; align-items:center; gap:10px; font-weight:700; color: var(--right-p-color);">
            <input type="checkbox" name="remember">
            Remember Me
          </label>
        </div>
      </div>

      <div class="member-register-actions" style="justify-content:flex-end; margin-top: 18px;">
        <button class="btn btn-primary member-register-submit" type="submit">
          ➜ Login
        </button>
      </div>
    </form>
   </div>
</div> 


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
<h2>Login</h2>

<form method="POST">
    <input name="email" placeholder="Email" required><br><br>
    <input name="password" type="password" placeholder="Password" required><br><br>

    <label>
        <input type="checkbox" name="remember">
        Remember Me
    </label>

    <br><br>
    <button type="submit">Login</button>
</form>
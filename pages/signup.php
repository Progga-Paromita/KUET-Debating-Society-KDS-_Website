<?php
require_once __DIR__ . "/../config/db.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$role = $_GET['role'] ?? "";


/* =========================
   FORM SUBMIT HANDLER
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* EMAIL CHECK */
    $checkEmailStmt = $conn->prepare(
        "SELECT email FROM member WHERE email=?
         UNION
         SELECT email FROM admin WHERE email=?
         UNION
         SELECT email FROM admin_requests WHERE email=?"
    );
    $checkEmailStmt->bind_param("sss", $email, $email, $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult && $checkEmailResult->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit;
    }


    /* ================= ADMIN ================= */
    if ($role == "admin") {

        $dept     = mysqli_real_escape_string($conn, $_POST['dept']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);

        $ins = $conn->prepare(
            "INSERT INTO admin_requests (name, email, password, dept, position, phone)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $ins->bind_param("ssssss", $name, $email, $password, $dept, $position, $phone);
        $ins->execute();


        echo "<script>
        alert('Admin request sent!');
        window.location=window.location.href;
      </script>";
        exit;
    }

    /* ================= MEMBER ================= */
    else {

        $roll         = mysqli_real_escape_string($conn, $_POST['roll']);
        $dept         = mysqli_real_escape_string($conn, $_POST['dept']);
        $session_year = mysqli_real_escape_string($conn, $_POST['session_year']);

        $ins = $conn->prepare(
            "INSERT INTO member
             (name, roll, dept, email, phone, session_year, password, profile_picture)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'default.png')"
        );
        $ins->bind_param("sssssss", $name, $roll, $dept, $email, $phone, $session_year, $password);
        $ins->execute();


        echo "<script>
        alert('Member registered successfully!');
        window.location=window.location.href;
      </script>";
        exit;
    }
}
?>

<link rel="stylesheet" href="signup.css">


<section style="height: 300px;"></section>
<div class="signup-wrap">
  <div class="signup-card">

    <div class="signup-header">
      <h2>Signup as <?= htmlspecialchars($role) ?></h2>
      <p>Fill the form carefully</p>
    </div>

    <form method="POST">

      <div class="grid">

        <div class="form-group">
          <label>Name</label>
          <input name="name" required>
        </div>

        <?php if ($role == "member"): ?>
        <div class="form-group">
          <label>Roll</label>
          <input name="roll" required>
        </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Department</label>
          <select name="dept" required>
            <option value="">Select Department</option>
            <option>CSE</option>
            <option>EEE</option>
            <option>BME</option>
            <option>MTE</option>
            <option>ARCH</option>
            <option>CE</option>
          </select>
        </div>

        <?php if ($role == "admin"): ?>
        <div class="form-group">
          <label>Position</label>
          <select name="position" required>
            <option value="">Select Position</option>
            <option>President</option>
            <option>Vice President</option>
            <option>Secretary</option>
            <option>Joint Secretary</option>
            <option>Treasurer</option>
            <option>Member</option>
          </select>
        </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Email</label>
          <input name="email" type="email" required>
        </div>

        <div class="form-group">
          <label>Phone</label>
          <input name="phone" type="tel" required>
        </div>

        <?php if ($role == "member"): ?>
        <div class="form-group">
          <label>Session</label>
          <select name="session_year" required>
            <option value="">Select Session</option>
            <option>1-1</option>
            <option>1-2</option>
            <option>2-1</option>
            <option>2-2</option>
            <option>3-1</option>
            <option>3-2</option>
            <option>4-1</option>
            <option>4-2</option>
          </select>
        </div>
        <?php endif; ?>

        <div class="form-group full">
          <label>Password</label>
          <input name="password" type="password" required>
        </div>

      </div>

      <button type="submit" class="btn">Signup</button>

    </form>

  </div>
</div>
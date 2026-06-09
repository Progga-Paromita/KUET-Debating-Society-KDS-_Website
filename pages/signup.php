<?php
$role = $_GET['role'] ?? "";

/* =========================
   FORM SUBMIT HANDLER
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* =========================
       EMAIL UNIQUE CHECK (ALL TABLES)
    ========================= */
    $checkEmail = mysqli_query($conn, "
        SELECT email FROM member WHERE email='$email'
        UNION
        SELECT email FROM admin WHERE email='$email'
        UNION
        SELECT email FROM admin_requests WHERE email='$email'
    ");

    if (mysqli_num_rows($checkEmail) > 0) {
        echo "<script>alert('❌ Email already exists!'); window.history.back();</script>";
        exit;
    }

    /* =========================
       ADMIN SIGNUP
    ========================= */
    if ($role == "admin") {

        $dept     = mysqli_real_escape_string($conn, $_POST['dept']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);

        $query = "
            INSERT INTO admin_requests
            (name, email, password, dept, position, phone)
            VALUES
            ('$name', '$email', '$password', '$dept', '$position', '$phone')
        ";

        if (!mysqli_query($conn, $query)) {
            die("Admin Insert Error: " . mysqli_error($conn));
        }

        echo "<script>
                alert('✅ Admin request sent! Wait for approval.');
                window.location='index.php';
              </script>";
        exit;
    }

    /* =========================
       MEMBER SIGNUP
    ========================= */
    else {

        $roll         = mysqli_real_escape_string($conn, $_POST['roll']);
        $dept         = mysqli_real_escape_string($conn, $_POST['dept']);
        $session_year = mysqli_real_escape_string($conn, $_POST['session_year']);

        $query = "
INSERT INTO member
(name, roll, dept, email, phone, session_year, password, profile_picture)
VALUES
('$name', '$roll', '$dept', '$email', '$phone', '$session_year', '$password', 'default.png')
";

if (!mysqli_query($conn, $query)) {
    echo "ERROR: " . mysqli_error($conn);
    exit;
}

        echo "<script>
                alert('✅ Member registered successfully!');
                window.location='index.php';
              </script>";
        exit;
    }
}
?>

<!-- =========================
     UI SECTION
========================= -->

<section style="height: 200px;"></section>

<div class="member-register-wrap">
  <div class="member-register-card">

    <h2>Signup as <?= htmlspecialchars($role) ?></h2>

    <form method="POST">

      <div class="member-register-grid">

        <!-- NAME -->
        <div class="form-group">
          <label>Name</label>
          <input name="name" required>
        </div>

        <!-- MEMBER: ROLL -->
        <?php if ($role == "member"): ?>
        <div class="form-group">
          <label>Roll</label>
          <input name="roll" required>
        </div>
        <?php endif; ?>

        <!-- DEPARTMENT -->
        <div class="form-group">
          <label>Department</label>
          <select name="dept" required>
            <option value="">Select</option>
            <option value="CSE">CSE</option>
            <option value="EEE">EEE</option>
            <option value="BME">BME</option>
            <option value="MTE">MTE</option>
            <option value="ARCH">ARCH</option>
            <option value="CE">CE</option>
          </select>
        </div>

        <!-- ADMIN: POSITION -->
        <?php if ($role == "admin"): ?>
        <div class="form-group">
          <label>Position</label>
          <select name="position" required>
            <option value="">Select Position</option>
            <option value="President">President</option>
            <option value="Vice President">Vice President</option>
            <option value="Secretary">Secretary</option>
            <option value="Joint Secretary">Joint Secretary</option>
            <option value="Treasurer">Treasurer</option>
            <option value="Member">Member</option>
          </select>
        </div>
        <?php endif; ?>

        <!-- EMAIL -->
        <div class="form-group">
          <label>Email</label>
          <input name="email" type="email" required>
        </div>

        <!-- PHONE -->
        <div class="form-group">
          <label>Phone</label>
          <input name="phone" type="tel" required>
        </div>

        <!-- MEMBER: SESSION -->
        <?php if ($role == "member"): ?>
        <div class="form-group">
          <label>Session</label>
          <select name="session_year" required>
            <option value="">Select</option>
            <option value="1-1">1-1</option>
            <option value="1-2">1-2</option>
            <option value="2-1">2-1</option>
            <option value="2-2">2-2</option>
            <option value="3-1">3-1</option>
            <option value="3-2">3-2</option>
            <option value="4-1">4-1</option>
            <option value="4-2">4-2</option>
          </select>
        </div>
        <?php endif; ?>

        <!-- PASSWORD -->
        <div class="form-group" style="grid-column:1/-1;">
          <label>Password</label>
          <input name="password" type="password" required>
        </div>

      </div>

      <button type="submit">Signup</button>

    </form>

  </div>
</div>
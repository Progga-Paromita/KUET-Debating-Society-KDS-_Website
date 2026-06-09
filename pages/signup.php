<?php
$role = $_GET['role'] ?? "";

/* =========================
   FORM SUBMIT HANDLER
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role == "admin") {

        $dept = $_POST['dept'];
        $position = $_POST['position'];

        /* =========================
           ROLE LIMIT SYSTEM
        ========================= */
        $limits = [
            "President" => 1,
            "Vice President" => 2,
            "Secretary" => 5,
            "Joint Secretary" => 10
            // Treasurer + Member = unlimited
        ];

        if (array_key_exists($position, $limits)) {

            $check = mysqli_query($conn,
                "SELECT COUNT(*) as total 
                 FROM admin 
                 WHERE position='$position'"
            );

            $data = mysqli_fetch_assoc($check);
            $count = $data['total'];

            if ($count >= $limits[$position]) {
                echo "❌ Sorry! $position position limit reached.";
                exit;
            }
        }

        // insert into admin_requests
        $sql = "INSERT INTO admin_requests(name,email,password,dept,position) 
                VALUES('$name','$email','$password','$dept','$position')";

        mysqli_query($conn, $sql);

        echo "Admin request sent. Wait for approval.";

    } else {

        // member signup
        $sql = "INSERT INTO member(name,email,password) 
                VALUES('$name','$email','$password')";

        mysqli_query($conn, $sql);

        echo "Member registered successfully!";
    }
}
?>

<!-- =========================
     UI SECTION
========================= -->

<section style="height: 300px;"></section>

<div class="member-register-wrap">
  <div class="member-register-card">

    <div class="member-register-header">
      <div class="member-register-badge">
        ✨ Signup Request
        <span style="font-size:12px; opacity:.85; font-family:'DM Mono', monospace;">
          Role: <?= htmlspecialchars($role) ?>
        </span>
      </div>

      <h2>Signup as <?= htmlspecialchars($role) ?></h2>

      <div class="member-register-subtitle">
        Fill in your details below.
        <?= ($role === 'admin') ? 'Admin signups require approval.' : 'You can sign in immediately after signup.' ?>
      </div>
    </div>

    <?php if ($role === 'admin'): ?>
      <div class="member-register-alert member-register-alert-success">
        <i>🛡️</i>
        <div>
          <div class="member-register-alert-title">Admin Request</div>
          <ul class="member-register-alert-list">
            <li>Account will be created after admin approval.</li>
            <li>Role limits apply (President, VP, etc.).</li>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <form class="member-register-form" method="POST">

      <div class="member-register-grid">

        <!-- NAME -->
        <div class="form-group">
          <label for="name">Full Name</label>
          <input id="name" name="name" placeholder="Your name" required>
        </div>

        <!-- EMAIL -->
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>

        <!-- =========================
             ADMIN FIELDS
        ========================= -->
        <?php if ($role === "admin"): ?>

          <!-- DEPARTMENT -->
          <div class="form-group">
            <label for="dept" class="form-label">
              Department <span class="required">*</span>
            </label>

            <select id="dept" name="dept" class="form-control" required>
              <option value="">Select Department</option>
              <option value="CSE">CSE</option>
              <option value="EEE">EEE</option>
              <option value="BME">BME</option>
              <option value="MTE">MTE</option>
              <option value="ARCH">ARCH</option>
              <option value="MSE">MSE</option>
              <option value="URP">URP</option>
              <option value="CE">CE</option>
              <option value="ChE">ChE</option>
              <option value="ME">ME</option>
              <option value="TE">TE</option>
              <option value="LE">LE</option>
              <option value="OTHERS">OTHERS</option>
            </select>
          </div>

          <!-- POSITION -->
          <div class="form-group">
            <label for="position" class="form-label">
              Position <span class="required">*</span>
            </label>

            <select id="position" name="position" class="form-control" required>
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

        <!-- PASSWORD -->
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Create a strong password" required>
        </div>

      </div>

      <!-- SUBMIT -->
      <div class="member-register-actions">
        <div class="member-register-hint">
          By signing up, you agree to follow the club’s rules and code of conduct.
        </div>

        <button class="btn btn-primary member-register-submit" type="submit">
          ➕ Signup
        </button>
      </div>

    </form>

  </div>
</div>
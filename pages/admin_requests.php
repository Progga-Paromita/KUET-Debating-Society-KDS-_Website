<?php
// only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

/* =========================
   APPROVE REQUEST
========================= */
if (isset($_GET['approve'])) {

    $id = (int) $_GET['approve'];

    // 1. Get request data
    $req = mysqli_query($conn, "SELECT * FROM admin_requests WHERE id=$id");
    $data = mysqli_fetch_assoc($req);

    if ($data) {

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $dept = $data['dept'];
        $position = $data['position'];

        // 2. Insert into admin table
        $insert = "INSERT INTO admin (name, email, password, dept, position)
                   VALUES ('$name', '$email', '$password', '$dept', '$position')";

        mysqli_query($conn, $insert);

        // 3. Update request status
        mysqli_query($conn, "UPDATE admin_requests SET status='approved' WHERE id=$id");

        echo "Admin approved successfully!";
    }
}

/* =========================
   REJECT REQUEST
========================= */
if (isset($_GET['reject'])) {

    $id = $_GET['reject'];

    // mark as rejected (you can also delete if you want)
    mysqli_query($conn, "UPDATE admin_requests SET status='rejected' WHERE id=$id");

    echo "Admin request rejected!";
}
?>
<section style="height: 300px;"></section>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="admin_requests.css">



<div class="admin-wrap">
  <div class="admin-page-wrap">

    <div class="panel admin-requests-panel">

      <!-- HEADER (CENTERED TITLE) -->
      <div class="panel-header center-header">
 
        <h2 class="panel-title">Pending Admin Requests</h2>
      </div>

      <div class="panel-body">

        <?php
        $result = mysqli_query($conn, "SELECT * FROM admin_requests WHERE status='pending'");
        while ($row = mysqli_fetch_assoc($result)) {
        ?>

          <div class="admin-list-card">

            <div class="request-row">

              <!-- Avatar -->
              <div class="request-avatar">
                <?= strtoupper(substr(trim($row['name']), 0, 1)) ?>
              </div>

              <!-- Content -->
              <div class="request-content">

                <div class="request-header">
                  <div>
                    <b>Position: <?= htmlspecialchars($row['position']) ?></b>
                    <!-- <b>Name: <?= htmlspecialchars($row['name']) ?></b> -->
                    <div class="request-email">Name: <?= htmlspecialchars($row['name']) ?></div>
                    <div class="request-email">Email: <?= htmlspecialchars($row['email']) ?></div>
                    <div class="request-email">Department: <?= htmlspecialchars($row['dept']) ?></div>
                    <!-- <div class="request-email">Position: <?= htmlspecialchars($row['position']) ?></div> -->
                    <!-- <div class="request-id">ID #<?= (int)$row['id'] ?></div> -->
                  </div>
                </div>

                <!-- Buttons -->
                <div class="request-actions">

                  <a class="btn-pill approve"
                     href="index.php?page=admin_requests&approve=<?= (int)$row['id'] ?>"
                     onclick="return confirm('Approve this admin request?');">
                     ✔ Approve
                  </a>

                  <a class="btn-pill reject"
                     href="index.php?page=admin_requests&reject=<?= (int)$row['id'] ?>"
                     onclick="return confirm('Reject this admin request?');">
                     ✖ Reject
                  </a>

                </div>

              </div>
            </div>

          </div>

        <?php } ?>

        <div class="action-buttons single">

    <a href="index.php?page=profile_admin" class="btn-back">
        ← Back to Profile
    </a>

</div>
      </div>
    </div>

  </div>
</div>
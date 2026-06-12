<?php
session_start();
require_once "config/db.php"; // adjust if your path is different

// ✅ ADMIN CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

// ✅ FILTER
$filter = $_GET['filter'] ?? 'all';

/* =========================
   MARK AS READ
========================= */
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    mysqli_query($conn, "UPDATE queries SET status='read' WHERE id=$id");
    header("Location: index.php?page=admin_queries&filter=$filter");
    exit;
}

/* =========================
   MARK AS UNREAD
========================= */
if (isset($_GET['unread'])) {
    $id = (int)$_GET['unread'];
    mysqli_query($conn, "UPDATE queries SET status='unread' WHERE id=$id");
    header("Location: index.php?page=admin_queries&filter=$filter");
    exit;
}

/* =========================
   DELETE QUERY
========================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM queries WHERE id=$id");
    header("Location: index.php?page=admin_queries&filter=$filter");
    exit;
}

/* =========================
   FETCH DATA
========================= */
$query = "SELECT * FROM queries";

if ($filter == 'unread') {
    $query .= " WHERE status='unread'";
} elseif ($filter == 'read') {
    $query .= " WHERE status='read'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);
?>

<!-- =========================
     PAGE UI
========================= -->

<section style="height: 300px;"></section>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="notifications.css">

<div class="admin-page-wrap admin-page-centered">

  <div class="panel" style="margin:0;">

    <!-- HEADER -->
    <div class="panel-header">
      <div class="panel-header-icon">🔔</div>
      <h2 class="panel-title">Notifications</h2>
    </div>

    <!-- FILTER BUTTON GROUP -->
    <div class="filter-group">

      <a href="?page=admin_queries&filter=unread"
         class="filter-pill <?= ($filter == 'unread') ? 'active' : '' ?>">
        🔴 Unread
      </a>

      <a href="?page=admin_queries&filter=read"
         class="filter-pill <?= ($filter == 'read') ? 'active' : '' ?>">
        🟢 Read
      </a>

      <a href="?page=admin_queries&filter=all"
         class="filter-pill <?= ($filter == 'all') ? 'active' : '' ?>">
        ⚪ All
      </a>

    </div>

    <!-- CHAT CONTAINER -->
    <div class="chat-container">

      <?php while ($q = mysqli_fetch_assoc($result)) { ?>

        <div class="chat-card <?= ($q['status'] == 'unread') ? 'unread' : '' ?>">

          <!-- AVATAR -->
          <div class="chat-avatar">
            <?= strtoupper(substr(trim($q['full_name']), 0, 1)) ?>
          </div>

          <!-- CONTENT -->
          <div class="chat-content">

            <!-- HEADER INFO -->
            <div class="chat-header">

              <div class="chat-info">

                <div class="info-row">
                  <span class="label">Name:</span>
                  <span class="value"><?= htmlspecialchars($q['full_name']) ?></span>
                </div>

                <div class="info-row">
                  <span class="label">Roll:</span>
                  <span class="value"><?= htmlspecialchars($q['roll']) ?></span>
                </div>

                <div class="info-row">
                  <span class="label">Dept:</span>
                  <span class="value"><?= htmlspecialchars($q['department']) ?></span>
                </div>

                <div class="info-row">
                  <span class="label">Email:</span>
                  <span class="value">
  <a class="email-link"
   href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($q['email']) ?>&su=Reply%20to%20your%20query"
   target="_blank">
   <?= htmlspecialchars($q['email']) ?>
</a>
</span>
                </div>

              </div>

              <span class="chat-status <?= htmlspecialchars($q['status']) ?>">
                <?= ($q['status'] == 'unread') ? 'New' : 'Read' ?>
              </span>

            </div>

            <!-- QUESTION -->
            <div class="chat-question">
              <div class="label">Question:</div>
              <div class="chat-bubble">
                📝 <?= htmlspecialchars($q['question']) ?>
              </div>
            </div>

            <!-- ACTIONS -->
            <div class="chat-actions">

              <?php if ($q['status'] == 'unread') { ?>
                <a class="chat-btn green"
                   href="?page=admin_queries&read=<?= $q['id'] ?>&filter=<?= $filter ?>">
                  Mark Read
                </a>
              <?php } else { ?>
                <a class="chat-btn green"
                   href="?page=admin_queries&unread=<?= $q['id'] ?>&filter=<?= $filter ?>">
                  Mark Unread
                </a>
              <?php } ?>

              <a class="chat-btn red"
                 href="?page=admin_queries&delete=<?= $q['id'] ?>&filter=<?= $filter ?>"
                 onclick="return confirm('Delete this query?')">
                Delete
              </a>

            </div>

          </div>
        </div>

      <?php } ?>

    </div>
  </div>
</div>

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

<a href="index.php?page=profile_admin">⬅ Back to Dashboard</a>

<h2>🔔 Pro Notifications</h2>

<!-- FILTER LINKS -->
<a href="?page=admin_queries&filter=unread"
   style="<?= $filter=='unread' ? 'font-weight:bold;' : '' ?>">Unread</a> |

<a href="?page=admin_queries&filter=read"
   style="<?= $filter=='read' ? 'font-weight:bold;' : '' ?>">Read</a> |

<a href="?page=admin_queries&filter=all"
   style="<?= $filter=='all' ? 'font-weight:bold;' : '' ?>">All</a>

<hr>

<?php while ($q = mysqli_fetch_assoc($result)) { ?>

<div style="border:1px solid #ccc;padding:15px;margin-bottom:15px;border-radius:8px;">

    <!-- USER INFO -->
    <b><?= htmlspecialchars($q['full_name']) ?></b> 
    (<?= htmlspecialchars($q['roll']) ?>)<br>

    <?= htmlspecialchars($q['department']) ?><br>
    <?= htmlspecialchars($q['email']) ?><br><br>

    <!-- QUESTION -->
    <p><?= htmlspecialchars($q['question']) ?></p>

    <!-- STATUS BADGE -->
    <?php if ($q['status'] == 'unread'): ?>
        <span style="color:red;font-weight:bold;">● New</span>
    <?php endif; ?>

    <br><br>

    <!-- TOGGLE BUTTON -->
    <?php if ($q['status'] == 'unread') { ?>

        <a href="?page=admin_queries&read=<?= $q['id'] ?>&filter=<?= $filter ?>"
           style="background:blue;color:white;padding:6px 10px;border-radius:4px;">
           Mark as Read
        </a>

    <?php } else { ?>

        <a href="?page=admin_queries&unread=<?= $q['id'] ?>&filter=<?= $filter ?>"
           style="background:orange;color:white;padding:6px 10px;border-radius:4px;">
           Mark as Unread
        </a>

    <?php } ?>

    <br><br>

    <!-- REPLY FORM -->
    <form method="POST" action="index.php?page=reply_query">
        <input type="hidden" name="id" value="<?= $q['id'] ?>">

        <textarea name="reply" placeholder="Write reply..." required
                  style="width:100%;height:80px;"></textarea><br><br>

        <button type="submit">Send Reply</button>
    </form>

    <br>

    <!-- DELETE -->
    <a href="?page=admin_queries&delete=<?= $q['id'] ?>&filter=<?= $filter ?>"
       style="color:red;"
       onclick="return confirm('Are you sure you want to delete this query?');">
       Delete
    </a>

</div>

<?php } ?>

<!-- SUCCESS MESSAGE -->
<?php if (isset($_GET['replied'])): ?>
    <div style="color:green;font-weight:bold;">
        ✅ Reply sent successfully!
    </div>
<?php endif; ?>
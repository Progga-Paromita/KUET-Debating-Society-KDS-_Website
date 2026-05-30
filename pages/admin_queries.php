<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

/* MARK SINGLE READ */
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    mysqli_query($conn, "UPDATE queries SET status='read' WHERE id=$id");
    header("Location: index.php?page=admin_queries");
    exit;
}

/* DELETE */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM queries WHERE id=$id");
    header("Location: index.php?page=admin_queries");
    exit;
}


$filter = $_GET['filter'] ?? 'unread';

$query = "SELECT * FROM queries";
if ($filter == 'unread') {
    $query .= " WHERE status='unread'";
} elseif ($filter == 'read') {
    $query .= " WHERE status='read'";
}
$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);
?>
<a href="index.php?page=profile_admin">⬅ Back to Dashboard</a>
<h2>🔔 Pro Notifications</h2>

<a href="?page=admin_queries&filter=unread">Unread</a> |
<a href="?page=admin_queries&filter=read">Read</a> |
<a href="?page=admin_queries">All</a>

<hr>

<?php while ($q = mysqli_fetch_assoc($result)) { ?>

<div style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">

    <b><?= $q['full_name'] ?></b> (<?= $q['roll'] ?>)<br>
    <?= $q['department'] ?><br>
    <?= $q['email'] ?><br><br>

    <p><?= $q['question'] ?></p>

    <?php if ($q['status'] == 'unread') { ?>
        <a href="?page=admin_queries&read=<?= $q['id'] ?>"
           style="background:blue;color:white;padding:5px;">
           Mark as Read
        </a>
    <?php } ?>

    <!-- REPLY BOX -->
    <form method="POST" action="index.php?page=reply_query">
        <input type="hidden" name="id" value="<?= $q['id'] ?>">

        <textarea name="reply" placeholder="Write reply..." required></textarea><br>

        <button type="submit">Send Reply</button>
    </form>

    <!-- DELETE -->
    <a href="?page=admin_queries&delete=<?= $q['id'] ?>"
       style="color:red;">Delete</a>

</div>

<?php } ?>

<?php if (isset($_GET['replied'])): ?>
    <div class="success-msg">
        ✅ Reply sent successfully!
    </div>
<?php endif; ?>
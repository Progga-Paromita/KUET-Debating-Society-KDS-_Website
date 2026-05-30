<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

/* =========================
   GET EVENT DATA
========================= */
$stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    echo "<p style='color:red;'>Event not found</p>";
    exit;
}

/* =========================
   UPDATE EVENT
========================= */
if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $date  = $_POST['event_date'];

    // 🔥 AUTO STATUS LOGIC
    $today = date("Y-m-d");
    $status = ($date >= $today) ? "upcoming" : "past";

    // KEEP OLD IMAGE
    $imageName = $event['image'];

    // IF NEW IMAGE UPLOADED
    if (!empty($_FILES['image']['name'])) {

        // Delete old image
        if (!empty($event['image']) && file_exists("uploads/" . $event['image'])) {
            unlink("uploads/" . $event['image']);
        }

        // Upload new image
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }

    // UPDATE DATABASE (WITH STATUS)
    $stmt = $conn->prepare("
        UPDATE events 
        SET title=?, description=?, event_date=?, image=?, status=?
        WHERE id=?
    ");

    $stmt->bind_param("sssssi", $title, $desc, $date, $imageName, $status, $id);
    $stmt->execute();

    // REDIRECT WITH SUCCESS MESSAGE
    header("Location: index.php?page=profile_admin&updated=1");
    exit;
}
?>

<!-- =========================
     UI PART
========================= -->

<h2>Edit Event</h2>

<?php if (!empty($event['image'])): ?>
    <p>Current Image:</p>
    <img src="uploads/<?= htmlspecialchars($event['image']) ?>" width="120"><br><br>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="title"
           value="<?= htmlspecialchars($event['title']) ?>"
           placeholder="Event Title" required><br><br>

    <textarea name="description"
              placeholder="Description"
              required><?= htmlspecialchars($event['description']) ?></textarea><br><br>

    <input type="date" name="event_date"
           value="<?= $event['event_date'] ?>" required><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit" name="update">Update Event</button>

</form>
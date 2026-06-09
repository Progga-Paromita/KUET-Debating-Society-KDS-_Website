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
    $category = $_POST['category'];
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
    SET title=?, description=?, event_date=?, image=?, status=?, category=?
    WHERE id=?
");

    $stmt->bind_param("ssssssi", $title, $desc, $date, $imageName, $status, $category, $id);
    $stmt->execute();

    // REDIRECT WITH SUCCESS MESSAGE
    header("Location: index.php?page=profile_admin&updated=1");
    exit;
}
?>

<!-- =========================
     UI PART
========================= -->

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="edit_event.css">
<section style="height: 300px;"></section>

<div class="panel-body-pro">
  <div class="admin-page-wrap admin-page-centered">

    <div class="panel-header">
      <div class="panel-header-icon">📅</div>
      <h2 class="panel-title">Edit Event</h2>
    </div>

    <div class="panel-body">

      <a class="admin-action-link" href="index.php?page=profile_admin">
        ⬅ Back to Dashboard
      </a>

      <?php if (!empty($event['image'])): ?>
        <div class="current-poster">
          <div class="poster-label">Current Poster</div>
          <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Poster">
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="event-form">

        <div class="form-grid">

          <div class="field">
            <label>Event Title</label>
            <input type="text" name="title"
                   value="<?= htmlspecialchars($event['title']) ?>"
                   placeholder="Event Title" required>
          </div>
          <div class="field">
  <label>Category</label>
  <select name="category" required>
    <option value="">Select Category</option>

    <option value="inter-university"
      <?= ($event['category'] == "inter-university") ? "selected" : "" ?>>
      Inter-university
    </option>

    <option value="workshop"
      <?= ($event['category'] == "workshop") ? "selected" : "" ?>>
      Workshop
    </option>

    <option value="competition"
      <?= ($event['category'] == "competition") ? "selected" : "" ?>>
      Competition
    </option>


  </select>
</div>

          <div class="field">
            <label>Event Date</label>
            <input type="date" name="event_date"
                   value="<?= htmlspecialchars($event['event_date']) ?>" required>
          </div>

          <div class="field full">
            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea>
          </div>

          <div class="field full">
            <label>Change Poster (optional)</label>
            <input type="file" name="image" accept="image/*">
          </div>

        </div>

        <div class="form-actions">
          <button type="submit" name="update" class="btn btn-primary">
            ✅ Update Event
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$admin_id = $_SESSION['user_id'];
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin WHERE id=$admin_id"));

/* =========================
   ADD EVENT
========================= */
if (isset($_POST['add_event'])) {

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];

    $imageName = "";

    if (!empty($_FILES['image']['name'])) {

    // ✅ FILE TYPE VALIDATION (ADD HERE)
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "Invalid file type! Only JPG, JPEG, PNG allowed.";
        exit;
    }

    // ✅ GENERATE UNIQUE NAME
    $imageName = time() . "_" . $_FILES['image']['name'];

    // ✅ UPLOAD PATH
    $target = "uploads/" . $imageName;

    // ✅ MOVE FILE
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        echo "Image upload failed!";
        exit;
    }
}

    $stmt = $conn->prepare("INSERT INTO events(title, description, event_date, image) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss", $title, $desc, $date, $imageName);
    $stmt->execute();

    header("Location: index.php?page=profile_admin");
    exit;
}

/* =========================
   ADD RESOURCE
========================= */
if (isset($_POST['add_resource'])) {

    $title = $_POST['title'];
    $link = $_POST['link'];
    $category = $_POST['category'];

    $fileName = "";

    if (!empty($_FILES['file']['name'])) {

        $fileName = time() . "_" . $_FILES['file']['name'];
        $target = "uploads/resources/" . $fileName;

        move_uploaded_file($_FILES['file']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("
        INSERT INTO resources(title, link, file, category)
        VALUES(?,?,?,?)
    ");
    $stmt->bind_param("ssss", $title, $link, $fileName, $category);
    $stmt->execute();

    header("Location: index.php?page=profile_admin");
    exit;
}

/* =========================
   DELETE EVENT
========================= */
if (isset($_GET['delete_event'])) {
    $id = (int) $_GET['delete_event'];

    mysqli_query($conn, "DELETE FROM events WHERE id=$id");

    header("Location: index.php?page=profile_admin");
    exit;
}

/* =========================
   DELETE RESOURCE
========================= */
if (isset($_GET['delete_resource'])) {
    $id = (int) $_GET['delete_resource'];

    mysqli_query($conn, "DELETE FROM resources WHERE id=$id");

    header("Location: index.php?page=profile_admin");
    exit;
}

/* =========================
   DELETE MEMBER
========================= */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    mysqli_query($conn, "DELETE FROM member WHERE id=$id");

    header("Location: index.php?page=profile_admin");
    exit;
}
?>


<section style="height: 300px;"></section>
<?php
/* ============================================================
   ADMIN PROFILE PAGE  —  profile_admin.php
   Requires: admin.css in the same directory (or adjust path)
   ============================================================ */
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">

<div class="admin-wrap">

  <!-- ── Admin Info Bar ──────────────────────────────────── -->
  <div class="panel">
    <div class="admin-bar">
      <div class="admin-identity">
        <div class="admin-avatar">
          <?= strtoupper(substr($admin['name'], 0, 1)) ?>
        </div>
        <div class="admin-details">
          <div class="name"><?= htmlspecialchars($admin['name']) ?></div>
          <div class="email"><?= htmlspecialchars($admin['email']) ?></div>
        </div>
      </div>
      <div class="admin-nav">
        <a href="index.php?page=admin_requests" class="nav-btn">
          📋 Admin Requests
        </a>
        <?php
          $notif_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM queries WHERE is_read = 0");
          $notif_count  = mysqli_fetch_assoc($notif_result)['total'];
        ?>
        <a href="index.php?page=admin_queries" class="nav-btn">
          🔔 Notifications
          <?php if ($notif_count > 0): ?>
            <span class="notif-badge"><?= $notif_count ?></span>
          <?php endif; ?>
        </a>
      </div>
    </div>
  </div>

  <!-- ── Add Event ───────────────────────────────────────── -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-header-icon">📅</div>
      <h2>Add New Event</h2>
    </div>
    <div class="panel-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="field full">
            <label>Event Title</label>
            <input name="title" placeholder="e.g. Annual Debate Championship" required>
          </div>
          <div class="field">
            <label>Category</label>
            <select name="category" required>
              <option value="">Select Category</option>
              <option value="inter-university">Inter-university</option>
              <option value="workshop">Workshop</option>
              <option value="competition">Competition</option>
            </select>
          </div>
          <div class="field">
            <label>Event Date</label>
            <input type="date" name="event_date" required>
          </div>
          <div class="field full">
            <label>Description</label>
            <textarea name="description" placeholder="Brief description of the event..."></textarea>
          </div>
          <div class="field full">
            <label>Event Poster</label>
            <input type="file" name="image" accept="image/*">
          </div>
        </div>
        <div style="margin-top:18px;">
          <button class="btn btn-primary" name="add_event">＋ Add Event</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Manage Events ───────────────────────────────────── -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-header-icon">📋</div>
      <h2>Manage Events</h2>
    </div>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Status</th>
            <th>Poster</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
          while ($e = $events->fetch_assoc()):
            $status_class = match($e['status']) {
              'active'   => 'status-active',
              'upcoming' => 'status-upcoming',
              default    => 'status-past',
            };
        ?>
          <tr>
            <td class="id-cell">#<?= $e['id'] ?></td>
            <td><strong><?= htmlspecialchars($e['title']) ?></strong></td>
            <td><?= date('M j, Y', strtotime($e['event_date'])) ?></td>
            <td><span class="status-badge <?= $status_class ?>"><?= $e['status'] ?></span></td>
            <td>
              <?php if (!empty($e['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($e['image']) ?>" alt="poster">
              <?php else: ?>
                <span class="no-img">No image</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="row-actions">
                <a class="act-btn act-edit" href="index.php?page=edit_event&id=<?= $e['id'] ?>">✏ Edit</a>
                <a class="act-btn act-delete"
                   href="index.php?page=profile_admin&delete_event=<?= $e['id'] ?>"
                   onclick="return confirm('Delete this event?')">🗑 Delete</a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Add Resource ────────────────────────────────────── -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-header-icon">📚</div>
      <h2>Add Resource</h2>
    </div>
    <div class="panel-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="field full">
            <label>Title</label>
            <input name="title" placeholder="Resource title" required>
          </div>
          <div class="field">
            <label>Category</label>
            <select name="category" required>
              <option value="General">General</option>
              <option value="Speech">Speech</option>
              <option value="Debate Guide">Debate Guide</option>
              <option value="Case Study">Case Study</option>
              <option value="Video">Video</option>
            </select>
          </div>
          <div class="field">
            <label>Link (optional)</label>
            <input name="link" placeholder="https://...">
          </div>
          <div class="field full">
            <label>Upload File</label>
            <input type="file" name="file" accept=".pdf,.doc,.ppt,.mp4">
          </div>
        </div>
        <div style="margin-top:18px;">
          <button class="btn btn-success" name="add_resource">＋ Add Resource</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Manage Resources ────────────────────────────────── -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-header-icon">📁</div>
      <h2>Manage Resources</h2>
    </div>
    <div class="panel-body">
      <?php
        $categories = ["General", "Speech", "Debate Guide", "Case Study", "Video"];
        foreach ($categories as $cat):
          $res = $conn->query("SELECT * FROM resources WHERE category='$cat' ORDER BY id DESC");
      ?>
      <div class="resource-category">
        <div class="cat-label"><span></span><?= $cat ?></div>
        <?php if ($res->num_rows > 0): ?>
          <table class="data-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($r['title']) ?></strong>
                  <div class="resource-id">#<?= $r['id'] ?></div>
                </td>
                <td>
                  <?php if (!empty($r['link'])): ?>
                    <a class="resource-link" href="<?= htmlspecialchars($r['link']) ?>" target="_blank">🔗 Open Link</a><br>
                  <?php endif; ?>
                  <?php if (!empty($r['file'])): ?>
                    <a class="resource-link" href="uploads/resources/<?= htmlspecialchars($r['file']) ?>" target="_blank">⬇ Download File</a>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="row-actions">
                    <a class="act-btn act-edit" href="index.php?page=edit_resource&id=<?= $r['id'] ?>">✏ Edit</a>
                    <a class="act-btn act-delete"
                       href="index.php?page=profile_admin&delete_resource=<?= $r['id'] ?>"
                       onclick="return confirm('Delete this resource?')">🗑 Delete</a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="empty-cat">No resources in this category yet.</p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── Member Management ───────────────────────────────── -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-header-icon">👥</div>
      <h2>Member Management</h2>
    </div>
    <div class="panel-body">
      <div class="members-grid">
      <?php
        $result = mysqli_query($conn, "SELECT * FROM member");
        while ($row = mysqli_fetch_assoc($result)):
          $parts    = explode(' ', trim($row['name']));
          $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
      ?>
        <div class="member-card">
          <div class="member-avatar"><?= $initials ?></div>
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <div class="member-email"><?= htmlspecialchars($row['email']) ?></div>
          <div class="member-id">ID #<?= $row['id'] ?></div>
          <div class="member-actions">
            <a class="m-edit" href="index.php?page=edit_member&id=<?= $row['id'] ?>">✏ Edit</a>
            <a class="m-delete"
               href="index.php?page=profile_admin&delete=<?= $row['id'] ?>"
               onclick="return confirm('Delete this member?')">🗑 Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
      </div>
    </div>
  </div>

</div><!-- /admin-wrap -->
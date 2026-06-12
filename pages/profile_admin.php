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
    $desc  = $_POST['description'];
    $date  = $_POST['event_date'];
    $category = $_POST['category']; // ✅ ADDED CATEGORY

    $imageName = "";

    if (!empty($_FILES['image']['name'])) {

        // FILE TYPE VALIDATION
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo "Invalid file type! Only JPG, JPEG, PNG allowed.";
            exit;
        }

        // UNIQUE NAME
        $imageName = time() . "_" . $_FILES['image']['name'];

        // UPLOAD PATH
        $target = "uploads/" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "Image upload failed!";
            exit;
        }
    }

    // ✅ UPDATED QUERY (CATEGORY ADDED)
    $stmt = $conn->prepare("
        INSERT INTO events (title, description, event_date, category, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $title, $desc, $date, $category, $imageName);
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

    $fileQuery = mysqli_query($conn, "SELECT file FROM resources WHERE id=$id");
    $fileData = mysqli_fetch_assoc($fileQuery);

    if (!empty($fileData['file'])) {
        $filePath = "uploads/resources/" . $fileData['file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

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

/* =========================
   UPDATE ADMIN PROFILE
========================= */
if (isset($_POST['update_profile'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];

    $profilePic = $admin['profile_pic']; // keep old

    if (!empty($_FILES['profile_pic']['name'])) {

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {

            $profilePic = time() . "_" . $_FILES['profile_pic']['name'];
            $target = "uploads/profile/" . $profilePic;

            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
        }
    }

    $stmt = $conn->prepare("UPDATE admin SET name=?, email=?, profile_pic=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $profilePic, $admin_id);
    $stmt->execute();

    header("Location: index.php?page=profile_admin");
    exit;
}



?>


<section style="height: 300px;"></section>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="admin_topbar.css">

<div class="admin-wrap">
  <h1 class="admin-name"> Hello 
        <?= htmlspecialchars($admin['position']) ?>!!!
    </h1>

<!-- RIGHT: ICON NAV -->
      <div class="admin-nav-icons">

        <!-- Requests -->
        <a href="index.php?page=admin_requests" class="icon-btn" title="Requests">
          📋
        </a>

        <!-- Notifications -->
        <?php
          $notif_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM queries WHERE is_read = 0");
          $notif_count  = mysqli_fetch_assoc($notif_result)['total'];
        ?>

        <a href="index.php?page=admin_queries" class="icon-btn" title="Notifications">
          🔔
          <?php if ($notif_count > 0): ?>
            <span class="icon-badge"><?= $notif_count ?></span>
          <?php endif; ?>
        </a>

      </div>

  <!-- ADMIN TOP BAR -->
  <div class="panel">

    <!-- PROFILE HEADER -->
    <div class="admin-bar modern-profile">
        <!-- LEFT SIDE -->
        <div class="profile-left">

            <!-- AVATAR -->
            <div class="profile-image">
                <?php if (!empty($admin['profile_pic'])): ?>
                    <img src="uploads/profile/<?= htmlspecialchars($admin['profile_pic']) ?>">
                <?php else: ?>
                    <div class="avatar-fallback modern-avatar">
                        <?= strtoupper(substr($admin['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- INFO -->
           <div class="profile-info">

    


    <div class="admin-details">

        <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>

        <p><strong>Department:</strong> <?= htmlspecialchars($admin['dept']) ?></p>

        <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>

        <p><strong>Position:</strong> <b><?= htmlspecialchars($admin['position']) ?></b></p>

    </div>

</div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="profile-actions">

            <a href="index.php?page=edit_profile&id=<?= $admin['id'] ?>" class="btn-edit-pro modern-btn">
                ✏ Edit Profile
            </a>

        </div>

    </div>

</div>

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
            <td class="id-cell"><?= $e['id'] ?></td>
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
      <h1>Manage Resources</h1>
    </div>
    <div class="panel-body">
      <?php
        $categories = ["General", "Speech", "Debate Guide", "Case Study", "Video"];
        foreach ($categories as $cat):
          $res = $conn->query("SELECT * FROM resources WHERE category='$cat' ORDER BY id DESC");
      ?>
      <div class="resource-category">
        <div class="cat-label"><h3><span></span><?= $cat ?></h3></div>
        <?php if ($res->num_rows > 0): ?>
          <table class="data-table">
            <thead>
              <tr>
                <th><h5>Title</h5></th>
                <th><h5>Content</h5></th>
                <th><h5>Actions</h5></th>
              </tr>
            </thead>
            <tbody>
            <?php while ($r = $res->fetch_assoc()): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($r['title']) ?></strong>
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
    <h1>Member Management</h1>
  </div>

  <div class="panel-body">
    <div class="members-grid">

    <?php
      $result = mysqli_query($conn, "SELECT * FROM member");

      while ($row = mysqli_fetch_assoc($result)):

        $parts = explode(' ', trim($row['name']));
        $initials = strtoupper(
          substr($parts[0], 0, 1) . 
          (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
        );

        // ✅ correct DB field
        $img = !empty($row['profile_picture']) ? $row['profile_picture'] : '';
    ?>

      <div class="member-card">

        <!-- AVATAR -->
        <div class="member-avatar">

          <?php if (!empty($img)) { ?>

            <img src="uploads/profile/<?= $img ?>"
                 alt="Profile"
                 style="width:100%; height:100%; object-fit:cover; border-radius:50%;">

          <?php } else { ?>

            <?= $initials ?>

          <?php } ?>

        </div>

        <!-- NAME -->
        <h3>Name: <?= htmlspecialchars($row['name']) ?></h3>

        <!-- EMAIL -->
        <div class="member-email">
          Email: <?= htmlspecialchars($row['email']) ?>
        </div>

        <!-- DEPARTMENT -->
        <div class="member-dept">
          Department: <?= htmlspecialchars($row['dept']) ?>
        </div>

        <!-- ACTIONS -->
        <div class="member-actions">
          <a class="m-edit" href="index.php?page=edit_member&id=<?= $row['id'] ?>">
            ✏ Edit
          </a>

          <a class="m-delete"
             href="index.php?page=profile_admin&delete=<?= $row['id'] ?>"
             onclick="return confirm('Delete this member?')">
            🗑 Delete
          </a>
        </div>

      </div>

    <?php endwhile; ?>

    </div>
  </div>
</div>
</div>
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
<h2>Admin Profile</h2>
<?php
$notif_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM queries WHERE is_read = 0");
$notif_count = mysqli_fetch_assoc($notif_result)['total'];
?>
<a href="index.php?page=admin_requests">Admin Requests</a><br>
<?php
$notif_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM queries WHERE is_read = 0");
$notif_count = mysqli_fetch_assoc($notif_result)['total'];
?>


<a href="index.php?page=admin_queries" style="position:relative;">
    🔔 Notifications

    <?php if ($notif_count > 0) { ?>
        <span style="
            background:red;
            color:white;
            border-radius:50%;
            padding:2px 7px;
            font-size:12px;
            position:absolute;
            top:-8px;
            right:-10px;
        ">
            <?= $notif_count ?>
        </span>
    <?php } ?>
</a>
<p><b>Name:</b> <?= $admin['name'] ?></p>
<p><b>Email:</b> <?= $admin['email'] ?></p>

<hr>

<!-- =========================
     ADD EVENT
========================= -->
<h2>📅 Add New Event</h2>

<form method="POST" enctype="multipart/form-data">
    <input class="form-control mb-2" name="title" placeholder="Event Title" required>

    <select class="form-control mb-2" name="category" required>
    <option value="">Select Category</option>
    <option value="inter-university">Inter-university</option>
    <option value="workshop">Workshop</option>
    <option value="competition">Competition</option>
</select>

    <textarea class="form-control mb-2" name="description"></textarea>

    <input class="form-control mb-2" type="date" name="event_date" required>

    <input class="form-control mb-2" type="file" name="image" accept="image/*">

    <button class="btn btn-primary" name="add_event">Add Event</button>
</form>

<!-- =========================
     MANAGE EVENTS
========================= -->
<h2>📅 Manage Events</h2>

<?php
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");

echo "<table border='1' cellpadding='10'>
<tr>
<th>ID</th>
<th>Title</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
<th>Poster</th>
</tr>";

while ($e = $events->fetch_assoc()) {

    echo "<tr>
        <td>{$e['id']}</td>
        <td>{$e['title']}</td>
        <td>{$e['event_date']}</td>
        <td>{$e['status']}</td>

        <td>
            <a class='btn btn-sm btn-warning' href='index.php?page=edit_event&id={$e['id']}'>Edit</a>
            <a class='btn btn-sm btn-danger' href='index.php?page=profile_admin&delete_event={$e['id']}'>Delete</a>
        </td>

        <td>";

    if (!empty($e['image'])) {
        echo "<img src='uploads/{$e['image']}' width='80'>";
    } else {
        echo "No Image";
    }

    echo "</td>
    </tr>";
}

echo "</table>";
?>

<hr>

<!-- =========================
     ADD RESOURCE
========================= -->
<h2>📚 Add Resource</h2>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="title" placeholder="Title" required>

    <!-- CATEGORY -->
    <select class="form-control mb-2" name="category" required>
        <option value="General">General</option>
        <option value="Speech">Speech</option>
        <option value="Debate Guide">Debate Guide</option>
        <option value="Case Study">Case Study</option>
        <option value="Video">Video</option>
    </select>

    <input class="form-control mb-2" name="link" placeholder="Link (optional)">

    <input class="form-control mb-2" type="file" name="file" accept=".pdf,.doc,.ppt,.mp4">

    <button class="btn btn-success" name="add_resource">Add Resource</button>

</form>

<!-- =========================
     MANAGE RESOURCES
========================= -->
<h2>📚 Manage Resources</h2>

<?php
$categories = ["General", "Speech", "Debate Guide", "Case Study", "Video"];

foreach ($categories as $cat) {

    echo "<h5 style='margin-top:20px;'>📁 $cat</h5>";

    $res = $conn->query("SELECT * FROM resources WHERE category='$cat' ORDER BY id DESC");

    if ($res->num_rows > 0) {

        echo "<table class='table table-bordered'>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Action</th>
        </tr>";

        while ($r = $res->fetch_assoc()) {

    echo "<tr>
        <td>{$r['id']}</td>
        <td>
            <b>{$r['title']}</b><br>
            <small><i>Category: {$r['category']}</i></small>
        </td>

        <td>";

    // LINK
    if (!empty($r['link'])) {
        echo "<a href='{$r['link']}' target='_blank'>Open Link</a><br>";
    }

    // FILE
    if (!empty($r['file'])) {
        echo "<a href='uploads/resources/{$r['file']}' target='_blank'>Download File</a>";
    }

    echo "</td>

        <td>
            <a class='btn btn-sm btn-warning' href='index.php?page=edit_resource&id={$r['id']}'>Edit</a>
            <a class='btn btn-sm btn-danger' href='index.php?page=profile_admin&delete_resource={$r['id']}'>Delete</a>
        </td>
    </tr>";
}

        echo "</table>";
    } else {
        echo "<p>No resources in $cat</p>";
    }
}
?>

<hr>

<!-- =========================
     MEMBER MANAGEMENT
========================= -->
<h2>👥 Member Management</h2>

<?php
$result = mysqli_query($conn, "SELECT * FROM member");

echo "<table border='1' cellpadding='10'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>
            <a href='index.php?page=edit_member&id={$row['id']}'>Edit</a> |
            <a href='index.php?page=profile_admin&delete={$row['id']}'
               onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}

echo "</table>";
?>


<hr>

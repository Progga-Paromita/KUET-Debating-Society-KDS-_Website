<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) $_GET['id'];

$admin = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM admin WHERE id=$id")
);

/* =========================
   UPDATE PROFILE (ONLY ONCE)
========================= */
if (isset($_POST['update_profile'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $dept  = $_POST['dept'];

    $profile_pic = $admin['profile_pic'];

    // image upload
    if (!empty($_FILES['profile_pic']['name'])) {

        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (in_array($ext, $allowed)) {

            $profile_pic = time() . "_" . $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/profile/" . $profile_pic);
        }
    }

    // UPDATE (ONLY ONE METHOD)
    $stmt = $conn->prepare("
        UPDATE admin 
        SET name=?, email=?, dept=?, profile_pic=? 
        WHERE id=?
    ");

    $stmt->bind_param("ssssi", $name, $email, $dept, $profile_pic, $id);
    $stmt->execute();

    header("Location: index.php?page=profile_admin");
    exit;
}
?>

<!-- =========================
     UI DESIGN
========================= -->
<section style="height: 300px;"></section>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<!-- <link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="admin_topbar.css"> -->
<link rel="stylesheet" href="edit_profile.css">

<div class="top-profile-info">
    <div class="role-pill">
        <?= htmlspecialchars($admin['position']) ?>
    </div>

    <h2 class="title">Edit Profile</h2>
    <p class="subtitle">Update your admin account information</p>
</div>
<div class="panel" id="edit-profile-panel">
 <div class="edit-page-wrapper">
    <div class="edit-card">

        

        <form method="POST" enctype="multipart/form-data">

            <!-- IMAGE -->
            <div class="image-section">

                <div class="image-preview">
                    <?php if (!empty($admin['profile_pic'])): ?>
                        <img id="previewImg" src="uploads/profile/<?= htmlspecialchars($admin['profile_pic']) ?>">
                    <?php else: ?>
                        <div class="avatar-fallback big">
                            <?= strtoupper(substr($admin['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <label class="upload-btn">
                    📷 Change Photo
                    <input type="file" name="profile_pic" accept="image/*" onchange="previewImage(event)">
                </label>

            </div>

            <!-- FIELDS -->
            <div class="form-grid">

                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                </div>

                 <div class="field">
                    <label>Department </label>
                    <input type="text" name="dept" value="<?= htmlspecialchars($admin['dept']) ?>" required>
                </div>

            </div>

            <!-- ACTIONS -->
            <div class="action-buttons">

    <a href="profile.php" class="btn-cancel">
        ← Back to Profile
    </a>

    <button type="submit" name="update_profile" class="btn-save">
        Save Changes ✔
    </button>

</div>

        </form>

    </div>

 </div>
</div>

<!-- IMAGE PREVIEW SCRIPT -->
<script>
function previewImage(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImg').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
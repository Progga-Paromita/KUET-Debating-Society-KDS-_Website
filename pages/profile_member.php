<?php
session_start();
require_once "config/db.php";

/* =========================
   SESSION CHECK
========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) $_SESSION['user_id'];

/* =========================
   GET MEMBER DATA
========================= */
$stmt = $conn->prepare("SELECT * FROM member WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

/* =========================
   UPDATE PROFILE
========================= */
if (isset($_POST['update_profile'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $dept  = $_POST['dept'];
    $phone = $_POST['phone'];

    $profile_pic = $member['profile_picture'];

    // IMAGE UPLOAD
    if (!empty($_FILES['profile_pic']['name'])) {

        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (in_array($ext, $allowed)) {

            $profile_pic = time() . "_" . $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/profile/" . $profile_pic);
        }
    }

    $stmt = $conn->prepare("
        UPDATE member 
        SET name=?, email=?, dept=?, phone=?, profile_picture=? 
        WHERE id=?
    ");

    $stmt->bind_param("sssssi", $name, $email, $dept, $phone, $profile_pic, $id);
    $stmt->execute();

    header("Location: index.php?page=profile_member");
    exit;
}
?>

<!-- =========================
     UI SECTION
========================= -->

<link rel="stylesheet" href="profile_member.css">
<section style="height: 200px;"></section>

<div class="profile-wrapper">

    <!-- PROFILE CARD -->
    <div class="profile-card">

        <div class="profile-header">
            <h2>Hello, <?= htmlspecialchars($member['name']) ?></h2>
            <p>Welcome to your profile</p>
        </div>

        <!-- IMAGE -->
        <div class="profile-image">
            <?php if (!empty($member['profile_picture'])): ?>
                <img src="uploads/profile/<?= htmlspecialchars($member['profile_picture']) ?>">
            <?php else: ?>
                <div class="avatar-fallback">
                    <?= strtoupper(substr($member['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- DETAILS -->
        <div class="profile-details">
            <p><strong>Name:</strong> <?= htmlspecialchars($member['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
            <p><strong>Department:</strong> <?= htmlspecialchars($member['dept']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($member['phone']) ?></p>
            <p><strong>Session:</strong> <?= htmlspecialchars($member['session_year']) ?></p>
        </div>

        <!-- EDIT BUTTON -->
        <button onclick="toggleEdit()" class="btn-edit">✏ Edit Profile</button>

    </div>

    <!-- EDIT FORM -->
    <div class="edit-card" id="editForm" style="display:none;">

        <h3>Edit Profile</h3>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Name</label>
                <input name="name" value="<?= htmlspecialchars($member['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" value="<?= htmlspecialchars($member['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input name="phone" value="<?= htmlspecialchars($member['phone']) ?>">
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="dept" required>
                    <option value="CSE" <?= $member['dept']=="CSE"?"selected":"" ?>>CSE</option>
                    <option value="EEE" <?= $member['dept']=="EEE"?"selected":"" ?>>EEE</option>
                    <option value="BME" <?= $member['dept']=="BME"?"selected":"" ?>>BME</option>
                    <option value="MTE" <?= $member['dept']=="MTE"?"selected":"" ?>>MTE</option>
                    <option value="ARCH" <?= $member['dept']=="ARCH"?"selected":"" ?>>ARCH</option>
                    <option value="CE" <?= $member['dept']=="CE"?"selected":"" ?>>CE</option>
                </select>
            </div>

            <div class="form-group">
                <label>Change Photo</label>
                <input type="file" name="profile_pic">
            </div>

            <button type="submit" name="update_profile" class="btn-save">
                Save Changes
            </button>

        </form>
    </div>

</div>

<!-- TOGGLE SCRIPT -->
<script>
function toggleEdit() {
    const form = document.getElementById("editForm");
    form.style.display = form.style.display === "none" ? "block" : "none";
}
</script>
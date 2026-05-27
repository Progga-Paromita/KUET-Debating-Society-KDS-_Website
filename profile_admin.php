<?php
require_once __DIR__ . "/config/db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=role_select_login");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin_data = [];
$members = [];
$error = "";
$success = "";
$edit_mode = false;
$edit_member = [];

// Handle delete member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'delete' && isset($_POST['member_id'])) {
        $member_id = (int)$_POST['member_id'];
        
        $stmt = mysqli_prepare(
            $connection,
            "DELETE FROM kds_db.member_db WHERE id = ? LIMIT 1"
        );
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $member_id);
            if (mysqli_stmt_execute($stmt)) {
                $success = "✅ Member deleted successfully!";
            } else {
                $error = "❌ Error deleting member.";
            }
            mysqli_stmt_close($stmt);
        }
    } 
    elseif ($action === 'update' && isset($_POST['member_id'])) {
        $member_id = (int)$_POST['member_id'];
        $full_name = trim($_POST['full_name'] ?? "");
        $email = trim($_POST['email'] ?? "");
        $phone = trim($_POST['phone'] ?? "");
        $dept = trim($_POST['dept'] ?? "");
        $semester = trim($_POST['semester'] ?? "");
        
        if ($full_name && $email && $phone && $dept && $semester) {
            $stmt = mysqli_prepare(
                $connection,
                "UPDATE kds_db.member_db SET full_name=?, email=?, phone=?, dept=?, semester=? WHERE id=? LIMIT 1"
            );
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssssi", $full_name, $email, $phone, $dept, $semester, $member_id);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "✅ Member updated successfully!";
                } else {
                    $error = "❌ Error updating member.";
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            $error = "❌ All fields are required.";
        }
    }
}

// Fetch admin data
$stmt = mysqli_prepare(
    $connection,
    "SELECT id, full_name, role, email, phone FROM kds_db.admin_db WHERE id = ? LIMIT 1"
);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $admin_data = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

// Fetch all members
$result = mysqli_query($connection, "SELECT id, full_name, roll, email, phone, dept, semester FROM kds_db.member_db ORDER BY id DESC");
if ($result) {
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<div style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <!-- Admin Profile Section -->
    <div style="
        background: var(--cream);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px var(--shadow-color);
        margin-bottom: 40px;
        border: 1px solid var(--border-light);
    ">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1 style="
                font-size: 32px;
                color: var(--text-dark);
                margin: 0;
                font-family: 'Playfair Display', serif;
            ">
                Admin Dashboard
            </h1>
            <a href="index.php" style="
                display: inline-block;
                padding: 10px 20px;
                background: var(--green);
                color: white;
                text-decoration: none;
                border-radius: 6px;
                transition: background 0.3s;
            " onmouseover="this.style.background='var(--green-dark)'" onmouseout="this.style.background='var(--green)'">
                Back Home
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (!empty($error)): ?>
            <div style="
                padding: 15px;
                background: #fee;
                color: #c33;
                border: 1px solid #fcc;
                border-radius: 6px;
                margin-bottom: 20px;
            ">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="
                padding: 15px;
                background: #efe;
                color: #3c3;
                border: 1px solid #cfc;
                border-radius: 6px;
                margin-bottom: 20px;
            ">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Admin Info -->
        <?php if (!empty($admin_data)): ?>
            <div style="
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 1fr;
                gap: 20px;
                margin-bottom: 30px;
            ">
                <div style="
                    background: var(--bg-main);
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid var(--green);
                ">
                    <label style="
                        font-size: 12px;
                        color: var(--green);
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">Name</label>
                    <p style="font-size: 16px; color: var(--text-dark); margin: 8px 0 0 0;">
                        <?php echo htmlspecialchars($admin_data['full_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>

                <div style="
                    background: var(--bg-main);
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid var(--green);
                ">
                    <label style="
                        font-size: 12px;
                        color: var(--green);
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">Role</label>
                    <p style="font-size: 16px; color: var(--text-dark); margin: 8px 0 0 0; text-transform: capitalize;">
                        <?php echo htmlspecialchars($admin_data['role'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>

                <div style="
                    background: var(--bg-main);
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid var(--green);
                ">
                    <label style="
                        font-size: 12px;
                        color: var(--green);
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">Email</label>
                    <p style="font-size: 14px; color: var(--text-dark); margin: 8px 0 0 0; word-break: break-all;">
                        <?php echo htmlspecialchars($admin_data['email'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>

                <div style="
                    background: var(--bg-main);
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid var(--green);
                ">
                    <label style="
                        font-size: 12px;
                        color: var(--green);
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">Phone</label>
                    <p style="font-size: 16px; color: var(--text-dark); margin: 8px 0 0 0;">
                        <?php echo htmlspecialchars($admin_data['phone'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            </div>

            <a href="index.php?logout=true" style="
                display: inline-block;
                padding: 10px 20px;
                background: #e74c3c;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 500;
                transition: background 0.3s;
            " onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                Logout
            </a>
        <?php endif; ?>
    </div>

    <!-- Members Management Section -->
    <div style="
        background: var(--cream);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px var(--shadow-color);
        border: 1px solid var(--border-light);
    ">
        <h2 style="
            font-size: 24px;
            color: var(--text-dark);
            margin: 0 0 25px 0;
            font-family: 'Playfair Display', serif;
        ">
            Member Management
        </h2>

        <p style="
            color: var(--green);
            font-weight: 500;
            margin-bottom: 20px;
        ">
            Total Members: <strong><?php echo count($members); ?></strong>
        </p>

        <?php if (empty($members)): ?>
            <div style="
                text-align: center;
                padding: 40px;
                color: var(--green);
                font-size: 16px;
            ">
                <p>📭 No members found</p>
            </div>
        <?php else: ?>
            <!-- Members Table -->
            <div style="overflow-x: auto;">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                ">
                    <thead>
                        <tr style="
                            background: var(--green);
                            color: white;
                            border-bottom: 2px solid var(--green-dark);
                        ">
                            <th style="padding: 15px; text-align: left;">ID</th>
                            <th style="padding: 15px; text-align: left;">Full Name</th>
                            <th style="padding: 15px; text-align: left;">Roll</th>
                            <th style="padding: 15px; text-align: left;">Email</th>
                            <th style="padding: 15px; text-align: left;">Phone</th>
                            <th style="padding: 15px; text-align: left;">Dept</th>
                            <th style="padding: 15px; text-align: left;">Semester</th>
                            <th style="padding: 15px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $index => $member): ?>
                            <tr style="
                                background: <?php echo $index % 2 === 0 ? 'var(--bg-main)' : 'transparent'; ?>;
                                border-bottom: 1px solid var(--border-light);
                                transition: background 0.3s;
                            " onmouseover="this.style.background='rgba(143, 174, 156, 0.1)'" onmouseout="this.style.background='<?php echo $index % 2 === 0 ? 'var(--bg-main)' : 'transparent'; ?>'">
                                <td style="padding: 15px; color: var(--text-dark);">
                                    <?php echo htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark); font-weight: 500;">
                                    <?php echo htmlspecialchars($member['full_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark);">
                                    <?php echo htmlspecialchars($member['roll'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark); word-break: break-all;">
                                    <?php echo htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark);">
                                    <?php echo htmlspecialchars($member['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark); text-transform: uppercase;">
                                    <?php echo htmlspecialchars($member['dept'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; color: var(--text-dark);">
                                    <?php echo htmlspecialchars($member['semester'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <button type="button" onclick="editMember(<?php echo htmlspecialchars(json_encode($member), ENT_QUOTES, 'UTF-8'); ?>)" style="
                                        padding: 6px 12px;
                                        margin-right: 8px;
                                        background: #3498db;
                                        color: white;
                                        border: none;
                                        border-radius: 4px;
                                        cursor: pointer;
                                        font-size: 12px;
                                        transition: background 0.3s;
                                    " onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                                        Edit
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" style="
                                            padding: 6px 12px;
                                            background: #e74c3c;
                                            color: white;
                                            border: none;
                                            border-radius: 4px;
                                            cursor: pointer;
                                            font-size: 12px;
                                            transition: background 0.3s;
                                        " onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="editModal" style="
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
">
    <div style="
        background: var(--cream);
        margin: 5% auto;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 12px var(--shadow-color);
    ">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="
                font-size: 22px;
                color: var(--text-dark);
                margin: 0;
                font-family: 'Playfair Display', serif;
            ">Edit Member</h2>
            <button onclick="closeEditModal()" style="
                background: none;
                border: none;
                font-size: 28px;
                cursor: pointer;
                color: var(--text-dark);
            ">&times;</button>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="member_id" id="edit_member_id">

            <div style="margin-bottom: 20px;">
                <label style="
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-dark);
                    font-weight: 500;
                ">Full Name</label>
                <input type="text" id="edit_full_name" name="full_name" required style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--border-light);
                    border-radius: 6px;
                    background: var(--bg-main);
                    color: var(--text-dark);
                    font-size: 14px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-dark);
                    font-weight: 500;
                ">Email</label>
                <input type="email" id="edit_email" name="email" required style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--border-light);
                    border-radius: 6px;
                    background: var(--bg-main);
                    color: var(--text-dark);
                    font-size: 14px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-dark);
                    font-weight: 500;
                ">Phone</label>
                <input type="text" id="edit_phone" name="phone" required style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--border-light);
                    border-radius: 6px;
                    background: var(--bg-main);
                    color: var(--text-dark);
                    font-size: 14px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-dark);
                    font-weight: 500;
                ">Department</label>
                <input type="text" id="edit_dept" name="dept" required style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--border-light);
                    border-radius: 6px;
                    background: var(--bg-main);
                    color: var(--text-dark);
                    font-size: 14px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-dark);
                    font-weight: 500;
                ">Semester</label>
                <input type="text" id="edit_semester" name="semester" required style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--border-light);
                    border-radius: 6px;
                    background: var(--bg-main);
                    color: var(--text-dark);
                    font-size: 14px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="
                    flex: 1;
                    padding: 12px;
                    background: var(--green);
                    color: white;
                    border: none;
                    border-radius: 6px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.3s;
                " onmouseover="this.style.background='var(--green-dark)'" onmouseout="this.style.background='var(--green)'">
                    Update Member
                </button>
                <button type="button" onclick="closeEditModal()" style="
                    flex: 1;
                    padding: 12px;
                    background: #95a5a6;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.3s;
                " onmouseover="this.style.background='#7f8c8d'" onmouseout="this.style.background='#95a5a6'">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editMember(member) {
    document.getElementById('edit_member_id').value = member.id;
    document.getElementById('edit_full_name').value = member.full_name;
    document.getElementById('edit_email').value = member.email;
    document.getElementById('edit_phone').value = member.phone;
    document.getElementById('edit_dept').value = member.dept;
    document.getElementById('edit_semester').value = member.semester;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('editModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

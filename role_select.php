<?php
$selectedRoleLabel = '';
$error = '';

// determine mode: 'login' or 'signin'
$mode = $mode ?? ($_GET['mode'] ?? 'login');
$mode = strtolower(trim((string)$mode));
if ($mode !== 'login' && $mode !== 'signin') {
  $mode = 'login';
}

// CSRF token
if (empty($_SESSION['role_select_csrf'])) {
  $_SESSION['role_select_csrf'] = bin2hex(random_bytes(16));
}
$csrf = (string)$_SESSION['role_select_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postedCsrf = $_POST['csrf'] ?? '';
  $postedMode = strtolower(trim((string)($_POST['mode'] ?? $mode)));

  if (!is_string($postedCsrf) || !hash_equals($csrf, $postedCsrf)) {
    $error = 'Session expired. Please try again.';
  } else {
    $role = strtolower(trim((string)($_POST['role'] ?? '')));

    if ($postedMode === 'login') {
      if ($role === 'admin') {
        $_SESSION['role'] = 'admin';
        header("Location: index.php?page=login_admin");
        exit;
      }

      if ($role === 'member') {
        $_SESSION['role'] = 'member';
        header("Location: index.php?page=login_member");
        exit;
      }
    } else { // signin
      if ($role === 'admin') {
        $_SESSION['role'] = 'admin';
        header("Location: index.php?page=signup_admin");
        exit;
      }

      if ($role === 'member') {
        $_SESSION['role'] = 'member';
        header("Location: index.php?page=signup_member");
        exit;
      }
    }

    $error = 'Invalid role selected.';
  }
}

// preserve selected value across renders
$selectedRole = $_POST['role'] ?? '';

?>

<div class="role-select-wrap">
  <section class="role-select-hero" aria-hidden="true"></section>

  <section class="role-select-content">
    <div class="role-select-card" role="region" aria-label="Select Your Role">
      <header class="role-select-header">
        <div class="role-select-badge"><i class="fa-solid fa-user-shield"></i> KDS Access</div>
        <h2>
          <?php echo ($mode === 'signin') ? 'Select Your Role For SignIn' : 'Select Your Role For Login'; ?>
        </h2>
        <p class="role-select-subtitle">Choose the account type you want to create. You can change it later.</p>
      </header>

      <form method="POST" class="role-select-form" autocomplete="off" novalidate>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">

        <fieldset class="role-fieldset" style="border:0;padding:0;margin:0;">
          <legend class="sr-only" style="position:absolute;left:-9999px;">Choose account role</legend>

          <?php if (!empty($error)) : ?>
            <div class="role-select-alert" role="alert">
              <i class="fa-solid fa-triangle-exclamation"></i>
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <div class="role-options" role="radiogroup" aria-label="Role options">
            <label class="role-option" tabindex="0">
              <input type="radio" name="role" value="member" required <?php echo ($selectedRole === 'member') ? 'checked' : ''; ?> >
              <span class="role-icon"><i class="fa-solid fa-graduation-cap"></i></span>
              <span class="role-title">Member</span>
              <span class="role-desc">Join debates, workshops, and events.</span>
            </label>

            <label class="role-option" tabindex="0">
              <input type="radio" name="role" value="admin" <?php echo ($selectedRole === 'admin') ? 'checked' : ''; ?> >
              <span class="role-icon"><i class="fa-solid fa-user-gear"></i></span>
              <span class="role-title">Admin</span>
              <span class="role-desc">Manage registrations and club operations.</span>
            </label>
          </div>

          <div class="role-select-actions">
            <button type="submit" class="btn-primary role-submit-btn">
              Continue
            </button>
          </div>
        </fieldset>
      </form>
    </div>
  </section>
</div>

<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<?php
// AUTO UPDATE EVENTS STATUS
mysqli_query($conn, "
    UPDATE events 
    SET status='past'
    WHERE event_date < CURDATE()
");
?>
<section style="height: 300px;"></section>
<section class="hero">
    <div class="hero-content">
      <h1>KUET Debating Society (KDS)</h1>
      <a href="index.php?page=role_select" class="btn-primary">Join today</a>
    </div>
  </section>

  <section class="content">
    <p>
      KUET Debating Society (KDS) is the student’s organization of debating society in Khulna University of
      Engineering and Technology. KDS has been working for the popularization of debate in the South-west
      region of our country for almost a decade.
    </p>
    <a href="index.php?page=learn_more" >
      <button class="btn-primary">Learn more...</button> 
    </a>
  </section>
<hr>

<!-- =========================
     UPCOMING EVENTS
========================= -->
<section class="calendar-section" id="calendar-section">
  <div class="calendar-content">
    <h2 class="calendar-title">Coming up...</h2>
    <p class="calendar-text">
      Stay updated with all the latest events, workshops, and debates
      organized by KUET Debating Society.
    </p>

    <a href="index.php?page=calender" class="calendar-btn">View calendar</a>
  </div>
</section>

<section class="members-section" id="upcoming">
  <div class="container">
<?php
$upcoming = mysqli_query($conn, "SELECT * FROM events WHERE status='upcoming' ORDER BY event_date ASC");

if ($upcoming && mysqli_num_rows($upcoming) > 0) {
   while ($e = mysqli_fetch_assoc($upcoming)) {

    echo "<div class='event-card'>
      <div class='event-content'>";

    if (!empty($e['image'])) {
        echo "<div class='event-image'>
                <img src='uploads/{$e['image']}' alt='{$e['title']}'>
              </div>";
    }

    echo "<h3>{$e['title']}</h3>
          <p>{$e['description']}</p>
          <div class='event-meta'>
            <span class='event-status upcoming'>Upcoming</span>
            <span class='event-category'>Date: {$e['event_date']}</span>
          </div>";

    echo "</div></div>";
   }
} else {
    echo "<p>No upcoming events.</p>";
}
?>

  </div>
</section>

<hr>

<!-- =========================
     CLUB MEMBERS
========================= -->
<section class="membership" id="membership">
    <div class="membership-container">

      <div class="left">
        <h1>Become a<br>member<br>today.</h1>
      </div>

      <div class="right">
        <p>
          Join Cambridge’s largest student society today for the opportunity to
          see world-class speakers and debaters as well as many other member benefits.
        </p>
        <a href="index.php?page=role_select" class="join-btn">Join today</a>
      </div>

    </div>
</section>

<hr>

<section class="members-section" id="members-section">
    <div class="container">
        <h2 class="section-title">Our Team Members</h2>

        <div class="members-grid">

        <?php
        // 🔥 SORT BY POSITION (President → Vice President → others)
        $admins = mysqli_query($conn, "
            SELECT * FROM admin
            ORDER BY 
                CASE 
                    WHEN position = 'President' THEN 1
                    WHEN position = 'Vice President' THEN 2
                    WHEN position = 'Secretary' THEN 3
                    WHEN position = 'Member' THEN 4
                    ELSE 5
                END
        ");

        while ($a = mysqli_fetch_assoc($admins)) {
        ?>

            <div class="member-card">

                <!-- AVATAR -->
                <div class="member-avatar">
                    <?php if (!empty($a['profile_pic'])): ?>
                        <img src="uploads/profile/<?= htmlspecialchars($a['profile_pic']) ?>">
                    <?php else: ?>
                        <?= strtoupper(substr($a['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <!-- NAME -->
                <h3 class="member-name">
                    <?= htmlspecialchars($a['name']) ?>
                </h3>

                <!-- POSITION -->
                <p class="member-position">
                    <strong><?= htmlspecialchars($a['position']) ?></strong>
                </p>

                <!-- EMAIL -->
                <p class="member-email">
                    <?= htmlspecialchars($a['email']) ?>
                </p>

                <!-- DEPARTMENT -->
                <p class="member-dept">
                    Dept: <?= htmlspecialchars($a['dept']) ?>
                </p>

            </div>

        <?php } ?>

        </div>
    </div>
</section>


<!-- Resources Section -->
<section class="resources-section" id="resources-section">
  <div class="container">
    <h2 class="section-title">Debating Resources</h2>
    <!-- FILTER BUTTONS -->
    <div class="events-filters">
      <button class="filter-btn active" data-filter="all">All Resources</button>
      <button class="filter-btn" data-filter="General">General</button>
      <button class="filter-btn" data-filter="Speech">Speech</button>
      <button class="filter-btn" data-filter="Debate Guide">Debate Guide</button>
      <button class="filter-btn" data-filter="Case Study">Case Study</button>
      <button class="filter-btn" data-filter="Video">Video</button>
    </div>
    <!-- GRID -->
    <div class="resources-grid">
    <?php
    $categories = ["General", "Speech", "Debate Guide", "Case Study", "Video"];
    foreach ($categories as $cat) {
        $res = mysqli_query($conn, "SELECT * FROM resources WHERE category='$cat'");
        while ($r = mysqli_fetch_assoc($res)) {
    ?>
        <div class="resource-card" data-category="<?php echo $cat; ?>">

            <div class="resource-icon">
                <?php
                if ($cat == "Video") {
                    echo '<i class="fa-brands fa-youtube"></i>';
                } elseif ($cat == "Speech") {
                    echo '<i class="fa-solid fa-microphone"></i>';
                } elseif ($cat == "Debate Guide") {
                    echo '<i class="fa-solid fa-file-pdf"></i>';
                } elseif ($cat == "Case Study") {
                    echo '<i class="fa-solid fa-book"></i>';
                } else {
                    echo '<i class="fa-solid fa-file"></i>';
                }
                ?>
            </div>
            <h3><?php echo $r['title']; ?></h3>
            <p><?php echo $r['description'] ?? ''; ?></p>
            <?php if (!empty($r['link'])): ?>
                <a href="<?php echo $r['link']; ?>" target="_blank" class="btn-primary">
                    Open Link
                </a>
            <?php endif; ?>

            <?php if (!empty($r['file'])): ?>
                <a href="uploads/resources/<?php echo $r['file']; ?>" target="_blank" class="btn-primary">
                    Download File
                </a>
            <?php endif; ?>
        </div>
    <?php
        }
    }
    ?>
    </div>
  </div>
</section>

<hr>

<!-- =========================
     PAST EVENTS
========================= -->
<section class="events-section" id="past-events">
  <div class="container">

    <h2 class="section-title">KDS Events</h2>

    <div class="events-filters">
      <button class="filter-btn active" data-filter="all">All Events</button>
      <button class="filter-btn" data-filter="inter-university">Inter-university</button>
      <button class="filter-btn" data-filter="workshop">Workshop</button>
      <button class="filter-btn" data-filter="competition">Competition</button>
    </div>

    <h3>Past Events</h3>

    <div class="events-grid">

    <?php
    $past = mysqli_query($conn, "SELECT * FROM events WHERE status='past' ORDER BY event_date DESC");

    while ($e = mysqli_fetch_assoc($past)) {

        $title = htmlspecialchars($e['title']);
        $desc = htmlspecialchars($e['description']);
        $category = htmlspecialchars($e['category'] ?? '');
        $image = $e['image'];
        $date = date("M d", strtotime($e['event_date']));
    ?>

        <div class="event-card past" data-category="<?php echo $category; ?>">

            <?php if (!empty($image)): ?>
                <div class="event-image">
                    <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="<?php echo $title; ?>">

                    <div class="event-date">
                        <?php echo $date; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="event-content">
                <h3><?php echo $title; ?></h3>
                <p><?php echo $desc; ?></p>

                <div class="event-meta">
                    <span class="event-category">
                        <?php echo $category; ?>
                    </span>

                    <span class="event-status past">
                        Past
                    </span>
                </div>
            </div>

        </div>

    <?php } ?>

    </div>
  </div>
</section>

<hr>

<!-- Ask a Question Section -->
<section class="join-form-section" id="ask-question-section">
  <div class="container">

    <h2 class="section-title">📩 Ask a Question</h2>

    <div class="form-content">

      <!-- LEFT SIDE FORM -->
      <div class="form-left">

        <form class="join-form" method="POST" action="index.php?page=submit_query">

          <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
          </div>

          <div class="form-group">
            <label>Roll</label>
            <input type="text" name="roll" required>
          </div>

          <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" required>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
          </div>

          <div class="form-group">
            <label>Your Question</label>
            <textarea name="question" required style="min-height: 120px;"></textarea>
          </div>

          <button type="submit" class="btn-primary form-submit">
            Submit Question
          </button>

        </form>

      </div>

      <!-- RIGHT SIDE INFO -->
      <div class="form-right">

        <h3>💡 Why Ask Questions?</h3>

        <ul class="benefits-list">
          <li>Get help from senior debaters</li>
          <li>Improve your debating skills faster</li>
          <li>Understand strategies and techniques</li>
          <li>Clarify confusing topics easily</li>
          <li>Become more confident in competitions</li>
        </ul>

        <p class="suggestion">
          <strong>Tip:</strong> Ask clear and specific questions to get better answers!
        </p>

      </div>

    </div>

  </div>
</section>
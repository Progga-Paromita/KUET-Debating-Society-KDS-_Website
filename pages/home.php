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

<section id="upcoming" class="members-section"
style="padding:60px 15px; background:#f6f8f5; color:#1f2937;">

<style>
#upcoming.dark {
  background: #0b1220 !important;
  color: #e5e7eb !important;
}

#upcoming .container{
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

/* CARD */
.event-card{
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(0,0,0,0.08);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
  transition: 0.3s ease;
}

#upcoming.dark .event-card{
  background: #111827;
  border: 1px solid rgba(255,255,255,0.1);
}

.event-card:hover{
  transform: translateY(-5px);
}

/* CONTENT */
.event-content{
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* IMAGE */
.event-image{
  width: 100%;
  height: 180px;
  overflow: hidden;
  border-radius: 12px;
}

.event-image img{
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: 0.3s;
}

.event-card:hover img{
  transform: scale(1.05);
}

/* TEXT */
.event-content h3{
  margin: 0;
  font-size: 18px;
}

.event-content p{
  margin: 0;
  font-size: 14px;
  color: #6b7280;
  line-height: 1.5;
}

#upcoming.dark .event-content p{
  color: #9ca3af;
}

/* META */
.event-meta{
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
}

/* STATUS */
.event-status{
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  background: rgba(143,174,156,0.2);
  color: #8fae9c;
}

/* DATE */
.event-category{
  color: #6b7280;
}

#upcoming.dark .event-category{
  color: #9ca3af;
}

/* EMPTY */
.members-section p{
  grid-column: 1 / -1;
  text-align: center;
  color: #6b7280;
}

/* RESPONSIVE */
@media(max-width:768px){
  .event-content{ padding:16px; }
  .event-image{ height:160px; }
}

@media(max-width:480px){
  #upcoming{
    padding:40px 10px;
  }

  .event-content h3{
    font-size:16px;
  }

  .event-content p{
    font-size:13px;
  }
}
</style>

<div class="container">

<?php
$upcoming = mysqli_query($conn, "SELECT * FROM events WHERE status='upcoming' ORDER BY event_date ASC");

if ($upcoming && mysqli_num_rows($upcoming) > 0) {
   while ($e = mysqli_fetch_assoc($upcoming)) {
?>

<div class="event-card">
  <div class="event-content">

    <?php if (!empty($e['image'])) { ?>
      <div class="event-image">
        <img src="uploads/<?= $e['image'] ?>" alt="<?= $e['title'] ?>">
      </div>
    <?php } ?>

    <h3><?= htmlspecialchars($e['title']) ?></h3>

    <p><?= htmlspecialchars($e['description']) ?></p>

    <div class="event-meta">
      <span class="event-status">Upcoming</span>
      <span class="event-category">Date: <?= $e['event_date'] ?></span>
    </div>

  </div>
</div>

<?php
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
          Join KDS largest student society today for the opportunity to
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

        <!-- INTERNAL CSS ONLY FOR AVATAR -->
        <style>
            .member-avatar{
                width: 90px;
                height: 90px;
                border-radius: 50%;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 10px;
                background: #8fae9c;
                color: #fff;
                font-weight: bold;
                font-size: 28px;
                border: 3px solid rgba(0,0,0,0.08);
            }

            .member-avatar img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
            }
        </style>

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

            <?php if (!empty($r['link']) || !empty($r['file'])): ?>
            <div class="resource-actions">

                <?php if (!empty($r['link'])): ?>
                    <a href="<?php echo $r['link']; ?>" target="_blank"
                       style="
                          display:inline-block;
                          padding:10px 16px;
                          margin-right:10px;
                          border-radius:10px;
                          text-decoration:none;
                          font-weight:600;
                          font-size:13px;
                          transition:0.3s;
                          background: #8fae9c;
                          color:#fff;
                          box-shadow:0 4px 12px rgba(143,174,156,0.25);
                       "
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(79,70,229,0.35)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(79,70,229,0.25)'"
                    >
                        🔗 Open Link
                    </a>
                <?php endif; ?>
                <div style="height:10px;"></div>
                <?php if (!empty($r['file'])): ?>
                    <a href="uploads/resources/<?php echo $r['file']; ?>" target="_blank"
                       style="
                          display:inline-block;
                          padding:10px 16px;
                          border-radius:10px;
                          text-decoration:none;
                          font-weight:600;
                          font-size:13px;
                          transition:0.3s;
                          background: #8fae9c;
                          color:#fff;
                          box-shadow:0 4px 12px rgba(5,150,105,0.25);
                       "
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(5,150,105,0.35)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(5,150,105,0.25)'"
                    >
                        ⬇ Download File
                    </a>
                <?php endif; ?>

            </div>
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

                    <span class="event-status past" style="background-color: #406450; color: #ffffff;">
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

    <select name="department" required
        style="
            width:100%;
            padding:12px 14px;
            border-radius:10px;
            font-size:14px;
            outline:none;
            cursor:pointer;
            transition:0.2s ease;

            /* LIGHT MODE */
            background:#ffffff;
            color:#1f2937;
            border:1px solid rgba(0,0,0,0.08);

            appearance:none;

            background-image: linear-gradient(45deg, transparent 50%, #6b7280 50%),
                              linear-gradient(135deg, #6b7280 50%, transparent 50%);
            background-position: calc(100% - 18px) calc(50% - 3px),
                                 calc(100% - 12px) calc(50% - 3px);
            background-size:6px 6px;
            background-repeat:no-repeat;
            padding-right:35px;
        "

        onfocus="this.style.borderColor='#8fae9c'; this.style.boxShadow='0 0 0 3px rgba(143,174,156,0.2)'"
        onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.boxShadow='none'"
    >
        <option value="">Select Department</option>
        <option value="CSE">CSE</option>
        <option value="EEE">EEE</option>
        <option value="BME">BME</option>
        <option value="MTE">MTE</option>
        <option value="ARCH">ARCH</option>
        <option value="CE">CE</option>
        <option value="ChE">ChE</option>
        <option value="ME">ME</option>
        <option value="TE">TE</option>
        <option value="LE">LE</option>
        <option value="OTHERS">OTHERS</option>
    </select>
</div>

<script>
/* DARK MODE SUPPORT (DEEP DARK THEME) */
if (document.documentElement.classList.contains('dark')) {
    const select = document.querySelector('select[name="department"]');
    if (select) {
        select.style.background = '#111827';   // deep dark
        select.style.color = '#e5e7eb';
        select.style.border = '1px solid rgba(255,255,255,0.08)';

        select.style.backgroundImage =
            'linear-gradient(45deg, transparent 50%, #9ca3af 50%), linear-gradient(135deg, #9ca3af 50%, transparent 50%)';
    }
}
</script>

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


<script>
document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".event-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {

            // remove active class
            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            const filter = this.getAttribute("data-filter");

            cards.forEach(card => {

                const category = card.getAttribute("data-category");

                if (filter === "all" || category === filter) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }

            });

        });
    });

});
</script>
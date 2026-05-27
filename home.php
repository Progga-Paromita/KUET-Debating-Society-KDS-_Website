<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<div>
    <!-- Hero -->
  <section class="hero">
    <div class="hero-content">
      <h1>KUET Debating Society (KDS)</h1>
      <a href="index.php?page=role_select_signin" class="btn-primary">Join today</a>
    
    </div>
  </section>

  <!-- Content -->
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



  <section class="calendar-section">
    <div class="calendar-content">
      <h2 class="calendar-title">Coming up...</h2>
      <p class="calendar-text">
        Stay updated with all the latest events, workshops, and debates
        organized by KUET Debating Society.
      </p>
      <a href="index.php?page=calender" class="calendar-btn">View calendar</a>
    </div>
  </section>


  <section class="membership">
    <div class="membership-container">

      <div class="left">
        <h1>Become a<br>member<br>today.</h1>
      </div>

      <div class="right">
        <p>
          Join Cambridge’s largest student society today for the opportunity to
          see world-class speakers and debaters as well as many other member benefits.
        </p>
        <a href="index.php?page=role_select_signin" class="join-btn">Join today</a>
      </div>

    </div>
  </section>



    <!-- Members Section -->
  <section class="members-section" id="members-section">
    <div class="container">
      <h2 class="section-title">Our Team Members</h2>
      <div class="members-grid">
        <div class="member-card">
          <div class="member-avatar">
            <i class="fa-solid fa-user"></i>
          </div>
          <h3>John Doe</h3>
          <p class="member-role">President</p>
        </div>
        <div class="member-card">
          <div class="member-avatar">
            <i class="fa-solid fa-user"></i>
          </div>
          <h3>Jane Smith</h3>
          <p class="member-role">Vice President</p>
        </div>
        <div class="member-card">
          <div class="member-avatar">
            <i class="fa-solid fa-user"></i>
          </div>
          <h3>Mike Johnson</h3>
          <p class="member-role">Secretary</p>
        </div>
        <div class="member-card">
          <div class="member-avatar">
            <i class="fa-solid fa-user"></i>
          </div>
          <h3>Sarah Wilson</h3>
          <p class="member-role">Treasurer</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Events Section -->
  <section class="events-section">
    <div class="container">
      <h2 class="section-title">KDS Events</h2>
      
      <!-- Filters -->
      <div class="events-filters">
        <button class="filter-btn active" data-filter="all">All Events</button>
        <button class="filter-btn" data-filter="inter-university">Inter-university</button>
        <button class="filter-btn" data-filter="workshop">Workshop</button>
        <button class="filter-btn" data-filter="competition">Competition</button>
      </div>

      <!-- Events Grid -->
      <div class="events-grid">
        <!-- Upcoming -->
        <div class="event-card upcoming" data-category="inter-university">
          <div class="event-image">
            <img src="img1.jpg" alt="Inter KUET Debate">
            <div class="event-date">Dec 15</div>
          </div>
          <div class="event-content">
            <h3>Inter KUET Debate Championship</h3>
            <p>National level debating competition with top universities.</p>
            <div class="event-meta">
              <span class="event-category">Inter-university</span>
              <span class="event-status upcoming">Upcoming</span>
            </div>
            <a href="#join-section" class="btn-primary event-btn">Register Now</a>
          </div>
        </div>

        <div class="event-card upcoming" data-category="workshop">
          <div class="event-image">
            <img src="img2.jpg" alt="Debate Workshop">
            <div class="event-date">Jan 10</div>
          </div>
          <div class="event-content">
            <h3>Advanced Debate Workshop</h3>
            <p>Master public speaking and argumentation techniques.</p>
            <div class="event-meta">
              <span class="event-category">Workshop</span>
              <span class="event-status upcoming">Upcoming</span>
            </div>
            <a href="#join-section" class="btn-primary event-btn">Register Now</a>
          </div>
        </div>

        <!-- Past -->
        <div class="event-card past" data-category="competition">
          <div class="event-image">
            <img src="img1.jpg" alt="KUET Debate Fest">
            <div class="event-date">Nov 20</div>
          </div>
          <div class="event-content">
            <h3>KUET Debate Fest 2024</h3>
            <p>Intra-university competition with 50+ participants.</p>
            <div class="event-meta">
              <span class="event-category">Competition</span>
              <span class="event-status past">Past</span>
            </div>
            <a href="#" class="btn-secondary event-btn">View Photos</a>
          </div>
        </div>

        <div class="event-card past" data-category="workshop">
          <div class="event-image">
            <img src="img2.jpg" alt="Public Speaking Workshop">
            <div class="event-date">Oct 5</div>
          </div>
          <div class="event-content">
            <h3>Public Speaking Fundamentals</h3>
            <p>Beginner workshop for 30 KDS members.</p>
            <div class="event-meta">
              <span class="event-category">Workshop</span>
              <span class="event-status past">Past</span>
            </div>
            <a href="#" class="btn-secondary event-btn">View Photos</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Resources Section -->
  <section class="resources-section">
    <div class="container">
      <h2 class="section-title">Debating Resources</h2>
      
      <div class="resources-grid">
        <!-- Debate Formats -->
        <div class="resource-card">
          <div class="resource-icon">
            <i class="fa-solid fa-file-pdf"></i>
          </div>
          <h3>Debate Formats Guide</h3>
          <p>British Parliamentary, Asian, World Schools - complete guide</p>
          <a href="resources/debate-formats.pdf" class="btn-primary" download>Download PDF</a>
        </div>

        <!-- Sample Motions -->
        <div class="resource-card">
          <div class="resource-icon">
            <i class="fa-solid fa-list"></i>
          </div>
          <h3>Sample Motions</h3>
          <p>200+ motions from past tournaments categorized by difficulty</p>
          <a href="#" class="btn-primary">View Motions</a>
        </div>

        <!-- Training Videos -->
        <div class="resource-card">
          <div class="resource-icon">
            <i class="fa-brands fa-youtube"></i>
          </div>
          <h3>Training Videos</h3>
          <p>Expert analysis of speeches and strategy sessions</p>
          <a href="https://www.youtube.com" target="_blank" class="btn-primary">Watch Videos</a>
        </div>

        <!-- Prep Materials -->
        <div class="resource-card">
          <div class="resource-icon">
            <i class="fa-solid fa-book"></i>
          </div>
          <h3>Prep Room Materials</h3>
          <p>Case templates, speaker roles, and research guides</p>
          <a href="#" class="btn-primary">Access Materials</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Join Registration Section -->
  <section class="join-form-section" id="join-section">
    <div class="container">
      <h2 class="section-title">Join KDS Today</h2>
      
      <div class="form-content">
        <div class="form-left">
          <form class="join-form" action="mailto:kds@kuet.ac.bd" method="post" enctype="text/plain">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="dept">Department</label>
              <input type="text" id="dept" name="dept" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn-primary form-submit">Submit Application</button>
          </form>
        </div>
        
        <div class="form-right">
          <h3>Why Join KDS?</h3>
          <ul class="benefits-list">
            <li>Develop critical thinking and public speaking skills</li>
            <li>Participate in inter-university debates and workshops</li>
            <li>Network with like-minded students and alumni</li>
            <li>Access exclusive resources and materials</li>
            <li>Join exciting events and competitions</li>
          </ul>
          <p class="suggestion"><strong>Suggestion:</strong> Regular attendance and active participation lead to leadership roles!</p>
        </div>
      </div>
    </div>
  </section>
</div>
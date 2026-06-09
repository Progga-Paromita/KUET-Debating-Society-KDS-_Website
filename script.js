document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".resource-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {

            // active button highlight
            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            const filter = btn.getAttribute("data-filter");

            cards.forEach(card => {

                if (filter === "all") {
                    card.style.display = "block";
                } else {
                    if (card.getAttribute("data-category") === filter) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                }

            });

        });
    });

});




document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".event-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {

            // active button
            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            const filter = btn.getAttribute("data-filter");

            cards.forEach(card => {

                if (filter === "all") {
                    card.style.display = "block";
                } else {
                    if (card.getAttribute("data-category") === filter) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                }

            });

        });
    });

});








// Theme toggle functionality
function initThemeToggle() {
  const themeToggle = document.getElementById('theme-toggle');
  if (!themeToggle) return;

  const html = document.documentElement;
  const icon = themeToggle.querySelector('i');

  // Load saved theme
  const savedTheme = localStorage.getItem('theme') || 'light';
  if (savedTheme === 'dark') {
    html.classList.add('dark');
    icon.className = 'fas fa-sun'; // Switch to sun icon
  }

  // Toggle theme
  themeToggle.addEventListener('click', (e) => {
    e.preventDefault();
    html.classList.toggle('dark');
    
    const isDark = html.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Update icon
    icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  });
}

document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.navbar .dropdown-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            toggles.forEach(t => t.classList.remove('active'));
            this.classList.toggle('active');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function () {
        toggles.forEach(t => t.classList.remove('active'));
    });

    // Initialize theme toggle
    initThemeToggle();

    // Join modal
    const joinNow = document.getElementById('join-now-link');
    const modal = document.getElementById('join-modal');
    const closeEls = modal ? modal.querySelectorAll('[data-close="true"]') : [];

    if (joinNow && modal) {
      const openModal = () => {
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
      };
      const closeModal = () => {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
      };

      joinNow.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
      });

      closeEls.forEach(el => {
        el.addEventListener('click', closeModal);
      });

      // Mode toggle
      const userBtn = document.getElementById('join-mode-user');
      const adminBtn = document.getElementById('join-mode-admin');
      const userForm = document.getElementById('join-form-user');
      const adminForm = document.getElementById('join-form-admin');

      const setMode = (mode) => {
        if (!userBtn || !adminBtn || !userForm || !adminForm) return;

        if (mode === 'admin') {
          userBtn.classList.remove('active');
          adminBtn.classList.add('active');
          userForm.style.display = 'none';
          adminForm.style.display = 'flex';
        } else {
          adminBtn.classList.remove('active');
          userBtn.classList.add('active');
          adminForm.style.display = 'none';
          userForm.style.display = 'flex';
        }
      };

      userBtn && userBtn.addEventListener('click', () => setMode('user'));
      adminBtn && adminBtn.addEventListener('click', () => setMode('admin'));

      // AJAX submit for both forms
      const bindSubmit = (form, messageEl) => {
        if (!form) return;
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          if (messageEl) messageEl.textContent = 'Submitting...';

          const formData = new FormData(form);
          const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
          });

          let data = null;
          try {
            data = await res.json();
          } catch (err) {
            if (messageEl) messageEl.textContent = 'Unexpected server response.';
            return;
          }

          if (!data || !data.ok) {
            if (messageEl) messageEl.textContent = (data && data.message) ? data.message : 'Registration failed.';
            return;
          }

          if (messageEl) messageEl.textContent = data.message;
          form.reset();
        
          // If there are no matching JS-only message elements (because the form is a normal HTML form),
          // still submit behavior works because JSON is returned.

        });
      };

      bindSubmit(document.getElementById('join-form-user'), document.getElementById('user-message'));
      bindSubmit(document.getElementById('join-form-admin'), document.getElementById('admin-message'));
    }
});

window.addEventListener("scroll", function () {
  const header = document.querySelector(".header");

  if (window.scrollY > 50) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});

// Events Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const eventCards = document.querySelectorAll('.event-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Remove active class from all buttons
      filterBtns.forEach(b => b.classList.remove('active'));
      // Add active class to clicked button
      btn.classList.add('active');

      const filter = btn.dataset.filter;

      eventCards.forEach(card => {
        if (filter === 'all' || card.dataset.category === filter) {
          card.style.display = 'block';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        } else {
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          setTimeout(() => {
            card.style.display = 'none';
          }, 300);
        }
      });
    });
  });
});


/**
 * UNITED LAW FIRM - CORE INTERACTIVE JAVASCRIPT
 * Includes: Sticky Header, Mobile Drawer Navigation, Scroll Reveal Animations,
 * FAQ Accordion Transitions, and Premium Multi-Step Consultation Form Wizard.
 */

document.addEventListener('DOMContentLoaded', () => {
  initStickyHeader();
  initMobileMenu();
  initScrollReveal();
  initFaqAccordion();
  initFormWizard();
  initFileAttachments();
});

/**
 * 1. Sticky Glassmorphic Header Animation
 */
function initStickyHeader() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  const handleScroll = () => {
    header.classList.toggle('scrolled', window.scrollY > 40);
  };

  window.addEventListener('scroll', handleScroll);
  handleScroll(); // Trigger initially in case page is refreshed while scrolled
}

/**
 * 2. Responsive Mobile Drawer Navigation Menu
 */
function initMobileMenu() {
  const menuBtn = document.querySelector('.menu-btn');
  // Handle both standard header and home hero headers
  const navLinks = document.querySelector('.nav-links') || document.querySelector('.nav-links--center');

  if (!menuBtn || !navLinks) return;

  // Toggle drawer state
  menuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isExpanded = navLinks.classList.toggle('show');
    menuBtn.setAttribute('aria-expanded', isExpanded);
    menuBtn.textContent = isExpanded ? '✕' : '☰'; // Modern switch icon
  });

  // Close drawer if user clicks outside of the menu
  document.addEventListener('click', (e) => {
    if (navLinks.classList.contains('show') && !navLinks.contains(e.target) && !menuBtn.contains(e.target)) {
      navLinks.classList.remove('show');
      menuBtn.setAttribute('aria-expanded', 'false');
      menuBtn.textContent = '☰';
    }
  });
}

/**
 * 3. IntersectionObserver scroll reveal animations
 */
function initScrollReveal() {
  const revealElements = document.querySelectorAll(
    '.service-card, .service-card--home, .feature, .value-card, .lawyer-card, ' +
    '.faq-item, .contact-card, .form-card, .ticket-side, .sec-head, .office-frame, ' +
    '.about-stat, .cta .container, .form-section'
  );

  if (revealElements.length === 0) return;

  const observerOptions = {
    threshold: 0.08,
    rootMargin: '0px 0px -40px 0px' // Triggers slightly before element enters view
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('show');
        observer.unobserve(entry.target); // Unobserve once shown for performance
      }
    });
  }, observerOptions);

  revealElements.forEach(el => {
    el.classList.add('fade-up');
    observer.observe(el);
  });
}

/**
 * 4. FAQ Accordion Animation with Smooth Height Transition
 */
function initFaqAccordion() {
  const faqItems = document.querySelectorAll('.faq-item');
  if (faqItems.length === 0) return;

  faqItems.forEach(item => {
    const questionButton = item.querySelector('.faq-q');
    if (!questionButton) return;

    questionButton.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');

      // Close all other accordion items for premium single-open behavior
      faqItems.forEach(otherItem => {
        if (otherItem !== item) {
          otherItem.classList.remove('open');
        }
      });

      // Toggle current item
      item.classList.toggle('open', !isOpen);
    });
  });
}

/**
 * 5. Premium Multi-Step Consultation Form Wizard
 */
function initFormWizard() {
  const form = document.getElementById('consultForm');
  if (!form) return;

  const steps = Array.from(form.querySelectorAll('.form-section'));
  const progressSteps = Array.from(form.querySelectorAll('.progress-step'));
  let currentStepIndex = 0;

  if (steps.length === 0) return;

  // Initialize display: show first step, hide others (styles handled in CSS)
  const updateWizardDisplay = () => {
    steps.forEach((step, idx) => {
      step.classList.toggle('is-active', idx === currentStepIndex);
    });

    // Update step progress indicator items
    progressSteps.forEach((progressStep, idx) => {
      progressStep.classList.remove('is-active', 'is-completed');
      if (idx === currentStepIndex) {
        progressStep.classList.add('is-active');
      } else if (idx < currentStepIndex) {
        progressStep.classList.add('is-completed');
      }
    });

    // Scroll smoothly to top of form wrapper so user remains oriented
    const formTopOffset = form.getBoundingClientRect().top + window.scrollY - 110;
    window.scrollTo({ top: formTopOffset, behavior: 'smooth' });
  };

  // Perform validation of required elements inside active step
  const validateCurrentStep = () => {
    const activeStep = steps[currentStepIndex];
    const inputs = Array.from(activeStep.querySelectorAll('input[required], select[required], textarea[required]'));
    
    let isStepValid = true;
    inputs.forEach(input => {
      // Remove any previous error outlines
      input.classList.remove('invalid-input');

      if (!input.checkValidity()) {
        isStepValid = false;
        input.classList.add('invalid-input');
      }
    });

    if (!isStepValid) {
      // Add custom styles for invalid input dynamically
      const firstInvalid = activeStep.querySelector('.invalid-input');
      if (firstInvalid) firstInvalid.focus();
    }

    return isStepValid;
  };

  // Dynamically append wizard buttons to each step section for clean structure
  steps.forEach((step, idx) => {
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'form-wizard-buttons';

    // Previous Button (Steps 2 and 3)
    if (idx > 0) {
      const prevBtn = document.createElement('button');
      prevBtn.type = 'button';
      prevBtn.className = 'btn btn-prev';
      prevBtn.textContent = 'السابق';
      prevBtn.addEventListener('click', () => {
        if (currentStepIndex > 0) {
          currentStepIndex--;
          updateWizardDisplay();
        }
      });
      buttonContainer.appendChild(prevBtn);
    }

    // Next Button (Steps 1 and 2) or Submit Button (Step 3)
    if (idx < steps.length - 1) {
      const nextBtn = document.createElement('button');
      nextBtn.type = 'button';
      nextBtn.className = 'btn btn-gold btn-next';
      nextBtn.innerHTML = 'التالي <span class="btn-arrow">←</span>';
      nextBtn.addEventListener('click', () => {
        if (validateCurrentStep()) {
          currentStepIndex++;
          updateWizardDisplay();
        }
      });
      buttonContainer.appendChild(nextBtn);
    } else {
      // Move the existing submit button to this container (Step 3 footer)
      const existingSubmitBtn = form.querySelector('.btn-submit-ticket');
      if (existingSubmitBtn) {
        buttonContainer.appendChild(existingSubmitBtn);
      }
    }

    // Append button container inside step section
    step.appendChild(buttonContainer);
  });

  // Handle final submission event
  form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Re-verify all steps before submit just in case
    let isFormValid = true;
    steps.forEach((step, idx) => {
      const inputs = Array.from(step.querySelectorAll('input[required], select[required], textarea[required]'));
      inputs.forEach(input => {
        if (!input.checkValidity()) {
          isFormValid = false;
          input.classList.add('invalid-input');
        }
      });
    });

    if (!isFormValid) {
      alert('الرجاء التأكد من تعبئة جميع الحقول المطلوبة بشكل صحيح.');
      return;
    }

    if (form.getAttribute('action')) {
      HTMLFormElement.prototype.submit.call(form);
      return;
    }

    // Premium dynamic Success Alert Modal
    const fullNameVal = document.getElementById('fullName')?.value || 'عزيزنا العميل';
    
    // Create modern alert card
    const backdrop = document.createElement('div');
    backdrop.className = 'success-backdrop';
    backdrop.innerHTML = `
      <div class="success-alert-card">
        <div class="success-alert-icon">✓</div>
        <h2>تم تقديم طلبك بنجاح!</h2>
        <p>شكرًا لك <strong>${fullNameVal}</strong>. تم تسجيل استشارتك القانونية بسرية تامة في نظام المتحدة.</p>
        <p class="sub-alert">سيقوم فريق المحامين لدينا بمراجعة طلبك والتواصل معك عبر تفاصيل الاتصال المقدمة في أقرب وقت ممكن.</p>
        <button type="button" class="btn btn-gold alert-close-btn">حسناً، فهمت</button>
      </div>
    `;

    document.body.appendChild(backdrop);

    // Close success overlay & reset form
    backdrop.querySelector('.alert-close-btn').addEventListener('click', () => {
      backdrop.remove();
      form.reset();
      const hint = document.getElementById('uploadHint');
      if (hint) hint.textContent = '';
      currentStepIndex = 0;
      updateWizardDisplay();
    });
  });

  // Initialize wizard displays
  updateWizardDisplay();
}

/**
 * 6. File Attachments Selection Label Hint update
 */
function initFileAttachments() {
  const fileInput = document.getElementById('attachments');
  const uploadHint = document.getElementById('uploadHint');

  if (!fileInput || !uploadHint) return;

  fileInput.addEventListener('change', () => {
    if (!fileInput.files.length) {
      uploadHint.textContent = '';
      return;
    }

    const filesArray = Array.from(fileInput.files);
    const names = filesArray.map(file => file.name).join(' ، ');
    uploadHint.textContent = `تم اختيار (${filesArray.length}) ملفات: ${names}`;
  });
}

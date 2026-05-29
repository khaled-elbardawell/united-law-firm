
const header = document.querySelector('.site-header');
window.addEventListener('scroll', () => {
  if(header){
    header.classList.toggle('scrolled', window.scrollY > 40);
  }
});

const observer = new IntersectionObserver((entries)=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      entry.target.classList.add('show');
    }
  });
},{threshold:.14});

document.querySelectorAll('.service-card,.service-card--home,.feature,.section-features-home .feature,.value-card,.lawyer-card,.faq-item,.contact-card,.form-card,.ticket-side,.sec-head,.office-frame,.about-stat,.cta .container,.form-section').forEach(el=>{
  el.classList.add('fade-up');
  observer.observe(el);
});

const menuBtn=document.querySelector('.menu-btn');
const navLinks=document.querySelector('.nav-links--center')||document.querySelector('.nav-links');
if(menuBtn&&navLinks){menuBtn.addEventListener('click',()=>navLinks.classList.toggle('show'));}
document.querySelectorAll('.faq-q').forEach(btn=>btn.addEventListener('click',()=>btn.closest('.faq-item').classList.toggle('open')));

document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',e=>{
  e.preventDefault();
  alert('تم إرسال طلب الاستشارة بنجاح، سيتم التواصل معك قريبًا.');
  form.reset();
  const hint=document.getElementById('uploadHint');
  if(hint) hint.textContent='';
}));

const fileInput=document.getElementById('attachments');
const uploadHint=document.getElementById('uploadHint');
if(fileInput&&uploadHint){
  fileInput.addEventListener('change',()=>{
    if(!fileInput.files.length){
      uploadHint.textContent='';
      return;
    }
    const names=[...fileInput.files].map(f=>f.name).join('، ');
    uploadHint.textContent='تم اختيار: ' + names;
  });
}

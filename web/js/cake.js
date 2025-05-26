$(document).ready(function () { 
  $('.product').hover(function () {
      $(this).addClass('animate__pulse');
  }, function () {
      $(this).removeClass('animate__pulse');
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const currentUrl = window.location.pathname;
  const navItems = document.querySelectorAll('.navbar-nav .nav-item');
  
  navItems.forEach(item => {
      const link = item.querySelector('a');
      if (link) {
          const linkUrl = link.getAttribute('href');
          // Сравниваем URL без учета параметров
          if (linkUrl && currentUrl.startsWith(linkUrl.split('?')[0])) {
              item.classList.add('active');
              link.classList.add('active');
          }
      }
  });
});

document.addEventListener("DOMContentLoaded", function () {
const btn = document.querySelector('.toggle-btn');
const fullText = document.querySelector('.history-full-text');

if (btn && fullText) {
  btn.addEventListener('click', function () {
    const isExpanded = fullText.style.maxHeight && fullText.style.maxHeight !== "0px";

    if (!isExpanded) {
      fullText.style.maxHeight = fullText.scrollHeight + "px";
      btn.textContent = 'Скрыть';
      btn.classList.add('active');
    } else {
      fullText.style.maxHeight = "0";
      btn.textContent = 'Показать больше';
      btn.classList.remove('active');
    }
  });

  fullText.style.overflow = "hidden";
  fullText.style.transition = "max-height 1.2s ease";
  fullText.style.maxHeight = "0";
}
});

document.addEventListener('DOMContentLoaded', () => {
  const sections = document.querySelectorAll('section, .hero-section, .new-arrivals, .why-choose-us, .awards, .history');
  const contactSection = document.querySelector('.contact');
  const footer = document.querySelector('footer');
  const navLinks = document.querySelectorAll('.navbar-nav .nav-link[href*="#"]');
  
  function onScroll() {
      let currentSectionId = '';
      const scrollY = window.scrollY + 100;
      const footerTop = footer.offsetTop;
      const windowBottom = scrollY + window.innerHeight;
  
      // Проверяем, виден ли футер
      const scrollBottom = window.scrollY + window.innerHeight;
      const fullHeight = document.documentElement.scrollHeight;
      
      // Проверяем, доскроллил ли пользователь до самого низа страницы
      const isAtPageBottom = Math.abs(scrollBottom - fullHeight) < 2; // небольшая погрешность
      
      if (isAtPageBottom) {
          currentSectionId = 'contact';
      } else {
          sections.forEach(section => {
              const sectionTop = section.offsetTop;
              const sectionHeight = section.offsetHeight;
              const sectionBottom = sectionTop + sectionHeight;
              
              if (scrollY >= sectionTop && scrollY < sectionBottom) {
                  currentSectionId = section.getAttribute('id');
              }
          });
      }
  
      navLinks.forEach(link => {
          link.classList.remove('active');
          const href = link.getAttribute('href');
          const hashIndex = href.indexOf('#');
          const anchor = hashIndex !== -1 ? href.substring(hashIndex) : '';
          
          if (anchor === `#${currentSectionId}`) {
              link.classList.add('active');
          }
      });
  }
  
  window.addEventListener('scroll', onScroll);
  onScroll();
  });
  document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для переключения видимости пароля
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.closest('.form-group').querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
            
            // Возвращаем фокус на поле ввода
            input.focus();
        });
    });
});